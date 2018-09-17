<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'email', 'profile_image', 'password',
    ];


    protected $appends = ['registered_at'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeFiltered($query){
		$query->select('id', 'name', 'last_name', 'email', 'profile_image');
    }

    public function getRegisteredAtAttribute(){
    	return $this->attributes['registered_at'] = strtolower((new Carbon($this->created_at))->format('d M Y'));
    }

    public function getProfileImageAttribute(){
        if(trim($this->attributes['profile_image'])){
            return asset('avatars/'.trim($this->attributes['profile_image']));
        }
        return '';
    }

	public function likes()
	{
		return $this->morphMany('App\Like', 'likable');
	}

	public function liked()
	{
		return $this->hasMany(Like::class, 'user_id');
	}
}
