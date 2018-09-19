<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    // $fillable
    protected $fillable = [ 'content', 'user_id' ];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
