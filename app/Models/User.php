<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    public function loadRelationshipCounts()
    {
        
        $this->loadCount(['microposts', 'followings', 'followers', 'favoritings']);
    }
    
    /**
     * このユーザがフォロー中のユーザ。（Userモデルとの関係を定義）
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    /**
     * このユーザをフォロー中のユーザ。（Userモデルとの関係を定義）
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    /**
     * $userIdで指定されたユーザをフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function follow($userId)
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        if ($exist || $its_me) {
            return false;
        } else {
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    /**
     * $userIdで指定されたユーザをアンフォローする。
     * 
     * @param  int $usereId
     * @return bool
     */
    public function unfollow($userId)
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        if ($exist && !$its_me) {
            $this->followings()->detach($userId);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 指定された$userIdのユーザをこのユーザがフォロー中であるか調べる。フォロー中ならtrueを返す。
     * 
     * @param  int $userId
     * @return bool
     */
    public function is_following($userId)
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    /**
     * このユーザとフォローの投稿に絞り込む。
     */
    public function feed_microposts()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
    
    
        
    
    
    
    /**
     * このユーザがお気に入りした投稿。（Userモデルとの関係を定義）
     */
    public function favoritings()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'fav_user_id', 'fav_post_id')->withTimestamps();
    }
    
    /**
     * $postIDで指定された投稿をお気に入りする。
     *
     */
    public function favorite($postId)
    {
        $exist = $this->is_favoriting($postId);
        if ($exist) {
            return false;
        } else {
            $this->favoritings()->attach($postId);
            return true;
        }
    }
    
    /**
     * $postIdで指定された投稿をお気に入り解除する。
     * 
     * @param  int $usereId
     * @return bool
     */
    public function unfavorite($postId)
    {
        $exist = $this->is_favoriting($postId);
        
        if ($exist) {
            $this->favoritings()->detach($postId);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 指定された$postIdの投稿をこのユーザがお気に入り中であるか調べる。お気に入り中ならtrueを返す。
     * 
     */
    public function is_favoriting($postId)
    {
        return $this->favoritings()->where('fav_post_id',$postId)->exists();
    }
    
    public function fav_microposts()
    {
        // このユーザがお気に入り中の投稿のidを取得して配列にする
        $postIds = $this->favoritings()->pluck('favorites.id')->toArray();
        
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('fav_post_id', $postIds);
    }
    

}

