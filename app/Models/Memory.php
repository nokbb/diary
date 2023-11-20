<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memory extends Model
{
    use HasFactory;
    //テーブル名
    protected $table = 'memories';

    //可変項目
    protected $fillable =
    [
        'post_id',
        'user_id',
        'file_path',
        'caption',
        'entry',
        'created_at',
        'updated_at',
        'category_id'
    ];

    /**
     * このメモリーを所有するユーザーを取得
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'photo_id');
    }


}
