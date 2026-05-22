<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Facades\CauserResolver;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AksesKoleksi extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'akses_koleksi';
    protected $primaryKey = 'akses_koleksi_id';

    protected $fillable = [
        'nama_akses_koleksi',
        'deskripsi',
        'url',
        'urutan',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) { $model->created_by = userName(); });
        static::updating(function ($model) { $model->updated_by = userName(); });
        static::deleting(function ($model) { $model->deleted_by = userName(); $model->update(); });
        static::restoring(function ($model) { $model->deleted_by = null; });
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
                return "{$aksi} akses dan koleksi";
            });
    }

    /**
     * Get data detail untuk edit atau list
     */
    public static function getDataDetail(array $filter = [], bool $get = true)
    {
        $query = self::select([
            'akses_koleksi.*'
        ])
        ->whereNull('akses_koleksi.deleted_at')
        ->orderBy('akses_koleksi.urutan', 'asc')
        ->orderBy('akses_koleksi.created_at', 'desc');

        if (isset($filter['akses_koleksi_id'])) {
            $query->where('akses_koleksi.akses_koleksi_id', $filter['akses_koleksi_id']);
        }

        if (isset($filter['is_active'])) {
            $query->where('akses_koleksi.is_active', $filter['is_active']);
        }

        return $get ? $query->get() : $query;
    }
}
