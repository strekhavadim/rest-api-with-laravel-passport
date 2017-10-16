<?php

namespace App\Http\Controllers\Api\User;

use App\Like;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function getAllUsers(){
		return $users = User::filtered()->get();
    }

	public function getCurrentUser(){
		$user = User::find(Auth::id());
		return $this->getUser($user);
	}

	public function updateCurrentUser(Request $request){
		$user = User::find(Auth::id());
		return $this->updateUser($request, $user);
	}

	public function getUser(User $user){
		return User::where('id', $user->id)->with('likes')->first();
	}

	public function updateUser(Request $request, User $user){
		if(Auth::user()->can('update', $user)){
			$this->validate($request, [
				'name' => 'string|max:255',
				'last_name' => 'string|max:255',
				'email' => 'string|email|max:255|unique:users,email',
				'password' => 'string|min:6|confirmed',
			]);

			if(request('name')) {
				$user->name = request('name');
			}
			if(request('last_name')) {
				$user->last_name = request('last_name');
			}
			if(request('email')) {
				$user->email = request('email');
			}
			if(request('password')) {
				$user->password = request('password');
			}
			$user->save();

			return $user;
		}else{
			return response()->json([], 403);
		}
	}

	public function likeUser(User $user){
		if(Auth::id() == $user->id){
			return response()->json([], 204);
		}
		$like = Like::where(['user_id' => Auth::id(), 'likable_id' => $user->id, 'likable_type' => 'users'])->first();
		if(!$like) {
			$like          = new Like();
			$like->user_id = Auth::id();
			$user->likes()->save( $like );
		}
		return $like;
	}

	public function updateCurrentUserProfileImage(Request $request){
		$user = User::find(Auth::id());
		return $this->updateUserProfileImage($request, $user);
	}

	public function updateUserProfileImage(Request $request, User $user){
		if(Auth::user()->can('update', $user)) {
			$this->validate($request, [
				'profile_image' => 'image|mimes:jpeg,png,bmp|max:5000',
				'delete_image' => 'boolean'
			]);
			if($request->hasFile('profile_image') && $request->file('profile_image')->isValid()){
				$this->deleteImage($user->profile_image);
				$file = $request->file('profile_image');
				$image_name = time().'-'.$file->getClientOriginalName();
				$file->storeAs('public/avatars', $image_name);
				$user->profile_image = $image_name;
				$user->save();
			}else{
				if(request('delete_image')){
					$this->deleteImage($user->profile_image);
					$user->profile_image = null;
					$user->save();
				}
			}
			return $user;
		}else{
			return response()->json([], 403);
		}
	}

	private function deleteImage($filename = null){
		if($filename && trim($filename)) {
			Storage::delete( 'public/avatars/' . $filename );
		}
	}

}
