<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Panduan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'panduan';
    protected $primaryKey = 'panduan_id';
    protected $fillable = [
        'judul',
        'deskripsi',
        'file',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $dates = ['deleted_at'];

    public static $exceptEdit = ['created_at', 'updated_at', 'deleted_at'];

    public static function getDataDetail($filter = [], $get = true)
    {
        $query = self::select([
            'panduan.*'
        ])
        ->whereNull('panduan.deleted_at');

        if (isset($filter['panduan_id'])) {
            $query->where('panduan.panduan_id', $filter['panduan_id']);
        }

        if (isset($filter['judul'])) {
            $query->where('panduan.judul', 'like', '%' . $filter['judul'] . '%');
        }

        return $get ? $query->get() : $query;
    }
}
