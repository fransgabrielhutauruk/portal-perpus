<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReqTurnitin extends Model
{
    use HasFactory, SoftDeletes;

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
        'status',
        'catatan_admin',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relasi ke tabel prodi
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id', 'prodi_id');
    }
}
