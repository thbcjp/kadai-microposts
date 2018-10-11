<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Micropost;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function microposts()
    {
        return $this->hasMany('App\Micropost');
    }
    
    // belongsToMany ユーザー自身がフォローしている方たちを取得するメソッド
    public function followings(){
        return $this->belongsToMany('App\User', 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    // belongsToMany ユーザー自身をフォロワーしてくれてるフォロワーさんたちを取得するメソッド
    public function followers(){
        return $this->belongsToMany('App\User', 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    
    
    public function is_following($userId){
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    public function follow($userId){
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 自分自身ではないかの確認
        $its_me = $this->id == $userId;
        
        if($exist || $its_me){
            // 既にフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    public function unfollow($userId){
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 自分自身ではないかの確認
        $its_me = $this->id == $userId;
        
        if($exist && !$its_me){
            // 既にフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }
    
    public function feed_microposts(){
        $follow_user_ids = $this->followings()->pluck('users.id')->toArray();
        $follow_user_ids[] = $this->id;
        return Micropost::whereIn('user_id', $follow_user_ids);
    }
    
    public function favorites(){
        return $this->belongsToMany('App\Micropost', 'user_favorite', 'user_id', 'favorite_id')->withTimestamps();
    }
    
    public function is_favorite($micropostId){
        return $this->favorites()->where('favorite_id', $micropostId)->exists();
        
    }
    
    public function favorite($micropostId){
        // 既にお気に入りに入れているかの確認
        $exist = $this->is_favorite($micropostId);
        
        if($exist){
            // 既に存在している場合は何もしない
            return false;
        } else {
            // お気に入りに入れていない場合は入れる
            $this->favorites()->attach($micropostId);
            return true;
        }
        
    }
    
    public function unfavorite($micropostId){
        $exist = $this->is_favorite($micropostId);
        
        if($exist){
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            return false;
        }
    }
}
