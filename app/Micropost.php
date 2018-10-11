<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Micropost extends Model
{
    // $fillable
    protected $fillable = [ 'content', 'user_id' ];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_favorite', 'favorite_id', 'user_id')->withTimestamps();
    }
}
