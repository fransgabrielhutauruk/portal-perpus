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
                return userInisial() . " {$aksi} table req_turnitin";
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
     * @param  mixed $where
     */
    public static function getDataDetail($where = [], $whereBinding = [], $get = true)
    {
        $query = DB::table('')
            ->selectRaw('a.*, p.nama_prodi')
            ->from('req_turnitin as a')
            ->leftJoin('dm_prodi as p', 'a.prodi_id', '=', 'p.prodi_id')
            ->whereNull('a.deleted_at')
            ->orderBy('a.created_at', 'desc');
        return $get ? $query->get() : $query;
    }
}
