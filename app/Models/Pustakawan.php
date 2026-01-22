<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pustakawan extends Model
{
    use HasFactory, SoftDeletes;

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
