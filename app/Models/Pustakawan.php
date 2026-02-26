<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Facades\CauserResolver;

class Pustakawan extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'pustakawan';
    protected $primaryKey = 'pustakawan_id';
    protected $fillable = [
        'nama',
        'email',
        'foto',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public static $exceptEdit = ['created_at', 'updated_at', 'deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) { $model->created_by = userInisial(); });
        static::updating(function ($model) { $model->updated_by = userInisial(); });
        static::deleting(function ($model) { $model->deleted_by = userInisial(); $model->update(); });
        static::restoring(function ($model) { $model->deleted_by = NULL; });
    }

    public function getActivitylogOptions(): LogOptions
    {
        CauserResolver::setCauser(causerActivityLog());
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->useLogName(env('APP_NAME'))
            ->setDescriptionForEvent(function ($eventName) {
                $aksi = eventActivityLogBahasa($eventName);
                return userInisial() . " {$aksi} table pustakawan";
            });
    }

    public static function getDataDetail($filter = [], $get = true)
    {
        $query = self::select([
            'pustakawan.*'
        ])
        ->whereNull('pustakawan.deleted_at');

        if (isset($filter['pustakawan_id'])) {
            $query->where('pustakawan.pustakawan_id', $filter['pustakawan_id']);
        }

        if (isset($filter['nama'])) {
            $query->where('pustakawan.nama', 'like', '%' . $filter['nama'] . '%');
        }

        if (isset($filter['email'])) {
            $query->where('pustakawan.email', 'like', '%' . $filter['email'] . '%');
        }

        return $get ? $query->get() : $query;
    }
}
