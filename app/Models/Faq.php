<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Facades\CauserResolver;

class Faq extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'faq';
    protected $primaryKey = 'faq_id';

    protected $fillable = [
        'pertanyaan',
        'jawaban',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

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
                return userInisial() . " {$aksi} table faq";
            });
    }

    /**
     * Get data detail untuk edit atau list
     */
    public static function getDataDetail($filter = [], $get = true)
    {
        $query = self::select([
            'faq.*'
        ])
        ->whereNull('faq.deleted_at')
        ->orderBy('faq.created_at', 'desc');

        if (isset($filter['faq_id'])) {
            $query->where('faq.faq_id', $filter['faq_id']);
        }

        return $get ? $query->get() : $query;
    }
}
