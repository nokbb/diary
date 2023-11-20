<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    //テーブル名
    protected $table = 'categories';
    //timestampsの自動生成をオフ
    public $timestamps = false;

    //可変項目
    protected $fillable =
    [
        'name',
        'icon'
    ];

    /**
     * このカテゴリーに属するメモリのリレーションを定義
     */
    public function memories()
    {
        return $this->hasMany(Memory::class);
    }
}
