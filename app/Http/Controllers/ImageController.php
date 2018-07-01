<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Intervention\Image\ImageManagerStatic as Image;

class ImageController extends Controller
{
    public function getAvatar($user_id)
    {
        $usersAvatar = User::findOrFail($user_id)->avatar;
        $storagePath = storage_path('app/public/avatars/' . $usersAvatar);
        return Image::make($storagePath)->resize(100, 100)->response();

        // $request->validate([
        //     'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);
        // $user = Auth::user();
        // $avatarName = $user->id . '_avatar_' . time() . '.' . request()->avatar->getClientOriginalExtension();
        // $request->avatar->storeAs('avatars', $avatarName);
        // $user->avatar = $avatarName;
        // $user->save();
        // return back()
        //     ->with('success', 'You have successfully upload image.');
    }
}
