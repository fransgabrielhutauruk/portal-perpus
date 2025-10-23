<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Berita extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'berita';
    protected $primaryKey = 'berita_id';
    protected $fillable = [
        'judul_berita',
        'isi_berita',
        'tanggal_berita',
        'user_id_author',
        'status_berita',
        'meta_desc_berita',
        'meta_keyword_berita',
        'slug_berita',
        'filename_berita',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public static $exceptEdit = ['created_at', 'updated_at', 'deleted_at'];

    public static function getDataDetail($filter = [], $get = true)
    {
        $query = self::select([
            'berita.*',
            'users.name as author_name'
        ])
        ->leftJoin('users', 'berita.user_id_author', '=', 'users.id')
        ->whereNull('berita.deleted_at');

        if (isset($filter['berita_id'])) {
            $query->where('berita.berita_id', $filter['berita_id']);
        }

        if (isset($filter['status_berita'])) {
            $query->where('berita.status_berita', $filter['status_berita']);
        }

        if ($get) {
            return $query->get();
        }

        return $query;
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id_author');
    }
}