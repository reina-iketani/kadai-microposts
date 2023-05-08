<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    use HasFactory;
    
    protected $fillable = ['content'];

    /**
     * この投稿を所有するユーザ。（ Userモデルとの関係を定義）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * この投稿をおきにいりしているユーザ。（Userモデルとの関係を定義）
     */
    public function favoriteposts()
    {
        return $this->belongsToMany(User::class, 'favorites', 'fav_post_id', 'fav_user_id')->withTimestamps();
    }
    
    
    
    

}
