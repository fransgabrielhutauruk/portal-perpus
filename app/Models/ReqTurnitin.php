<?php

namespace App\Models;

use App\Models\Dimension\Prodi;
use App\Enums\StatusRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Facades\CauserResolver;

class ReqTurnitin extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'req_turnitin';
    protected $primaryKey = 'reqturnitin_id';

    protected $fillable = [
        'prodi_id',
        'nama_dosen',
        'inisial_dosen',
        'nip',
        'email_dosen',
        'jenis_dokumen',
        'judul_dokumen',
        'file_dokumen',
        'keterangan',
        'status_req',
        'catatan_admin',
        'file_hasil_turnitin',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'prodi_id' => 'integer',
        'status_req' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
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
                return "{$aksi} req turnitin";
            });
    }

    /**
     * Relasi ke tabel prodi
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id', 'prodi_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return self::getStatusBadge($this->status_req);
    }

    public static function getStatusBadge($statusReq): string
    {
        $badges = [
            StatusRequest::MENUNGGU->value => '<span class="badge badge-warning bg-warning text-dark rounded-pill">Menunggu</span>',
            StatusRequest::DISETUJUI->value => '<span class="badge badge-success bg-success rounded-pill">Disetujui</span>',
            StatusRequest::DITOLAK->value => '<span class="badge badge-danger bg-danger rounded-pill">Ditolak</span>',
        ];

        return $badges[$statusReq] ?? '<span class="badge badge-secondary rounded-pill">Unknown</span>';
    }

    /**
     * fungsi kustom untuk menghasilkan data model secara detail (rinci) dengan seluruh kemungkinan join yang terjadi
     *
     * @param array $where Filter conditions
     * @param array $whereBinding Binding values for queries
     * @param bool  $get Whether to execute and return results (true) or return query builder (false)
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder
     */
    public static function getDataDetail($where = [], $whereBinding = [], $get = true)
    {
        $query = DB::table('req_turnitin')
            ->selectRaw('req_turnitin.*, dm_prodi.nama_prodi')
            ->leftJoin('dm_prodi', 'req_turnitin.prodi_id', '=', 'dm_prodi.prodi_id')
            ->whereNull('req_turnitin.deleted_at')
            ->orderBy('req_turnitin.created_at', 'desc');

        return $get ? $query->get() : $query;
    }
}
