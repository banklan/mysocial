<?php

namespace App;

use App\Like;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    protected $fillable = ['content', 'user_id'];

    protected $with = ['user', 'comments', 'likes'];


    // order according to date
    protected static function boot(){
        parent::boot();
        static::addGlobalScope('order', function(Builder $builder){
            $builder->orderBy('created_at', 'desc');
        });
    }


    public function user(){
        return $this->belongsTo('App\User');
    }

    public function comments(){
        return $this->hasMany('App\Comment');
    }

    public function likes(){
        return $this->hasMany('App\Like');
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = ucfirst($value);
    }
}
