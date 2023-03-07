<?php

namespace App;

use stdClass;
use App\Follow;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use App\User;

class User extends Authenticatable implements JWTSubject
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

    protected $appends = ['fullname'];

    // protected $with = ['followings'];
    // protected $appends = ['notFollowing'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getFullnameAttribute()
    {
        $fullname = $this->first_name." ".$this->last_name;
        return $fullname;
    }

    public function posts(){
        return $this->hasMany('App\Post');
    }

    // auth user follow another user if already not following
    public function follow(User $user) {
        if(!$this->isFollowing($user)) {
            Follow::create([
                'user_id' => auth()->id(),
                'following_id' => $user->id
            ]);
        }
    }

    //auth user to unfollow another user
    public function unfollow(User $user) {
        Follow::where('user_id', auth()->id())->where('following_id', $user->id)->delete();
    }

    //check if following a user
    public function isFollowing(User $user) {
        return $this->following()->where('users.id', $user->id)->exists();
    }


    public function following() {
        return $this->hasManyThrough(User::class, Follow::class, 'user_id', 'id', 'id', 'following_id');
    }

    public function followers() {
        return $this->hasManyThrough(User::class, Follow::class, 'following_id', 'id', 'id', 'user_id');
    }
}
