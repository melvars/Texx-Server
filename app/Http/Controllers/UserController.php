<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\User;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
    public function Profile()
    {
        $user = Auth::user();
        return view('profile', compact('user', $user));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $user = Auth::user();
        $avatarName = $user->id . '.' . $request->avatar->getClientOriginalExtension();
        $fittedAvatar = Image::make($request->avatar)->fit(256)->encode();
        Storage::put('avatars/' . $avatarName, (string) $fittedAvatar);
        $user->avatar = $avatarName;
        $user->save();
        return response()->json(array('success'=>'You have successfully uploaded the avatar.'));
    }
}
