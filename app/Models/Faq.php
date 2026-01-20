<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'faq';
    protected $primaryKey = 'faq_id';

    protected $fillable = [
        'pertanyaan',
        'jawaban',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

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
