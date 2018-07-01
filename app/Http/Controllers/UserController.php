<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

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
        $avatarName = $user->id . '_avatar' . time() . '.' . request()->avatar->getClientOriginalExtension();
        $request->avatar->storeAs('avatars', $avatarName);
        $user->avatar = $avatarName;
        $user->save();
        return back()
            ->with('success', 'You have successfully upload image.');
    }
}
