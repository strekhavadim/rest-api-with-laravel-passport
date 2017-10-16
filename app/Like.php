<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{

	public function likable()
	{
		return $this->morphTo();
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

}
