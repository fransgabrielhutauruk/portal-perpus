<?php

/*
 * Author: @wahyudibinsaid
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Facades\CauserResolver;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Kaperpus extends Model
{
    use SoftDeletes;
    use LogsActivity;

    /**
     * definisi nama table
     *
     * @var string
     */
    public $table = 'kaperpus';

    /**
     * set kolom primary key, default primary key kolom adalah id
     *
     * @var string
     */
    protected $primaryKey = 'kaperpus_id';

    /**
     * kolom-kolom yang dapat di ubah data nya
     *
     * @var array
     */
    public $fillable = [
        'nama_kaperpus',
        'ttd_kaperpus',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * kolom yang tipe data hasil nya perlu diubah
     *
     * @var array
     */
    protected $casts = [
        'kaperpus_id' => 'string',
        'is_active'   => 'boolean',
    ];

    public static array $exceptEdit = [
        'kaperpus_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * fungsi yang di panggil saat event crud dijalankan
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = userInisial();
        });

        static::updating(function ($model) {
            $model->updated_by = userInisial();
        });

        static::deleting(function ($model) {
            $model->deleted_by = userInisial();
            $model->update();
        });

        static::restoring(function ($model) {
            $model->deleted_by = NULL;
        });
    }

    /**
     * fungsi yang di panggil setelah proses crud selesai dijalankan (event trigger) untuk proses pencatatan log
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        CauserResolver::setCauser(auth()->user());
        return LogOptions::defaults()
            ->useLogName('kaperpus')
            ->logOnly(['*'])
            ->logOnlyDirty();
    }

    /**
     * Ambil data kaperpus yang aktif
     *
     * @return self|null
     */
    public static function getActive(): ?self
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Get data detail for DataTable
     *
     * @param array $filter
     * @param bool $get
     * @return mixed
     */
    public static function getDataDetail(array $filter = [], bool $get = false)
    {
        $query = DB::table('kaperpus')
            ->select([
                'kaperpus_id',
                'nama_kaperpus',
                'ttd_kaperpus',
                'is_active',
                'created_at',
            ])
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc');

        if (!empty($filter)) {
            $query->where($filter);
        }

        return $get ? $query->get() : $query;
    }
}
