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
        return Image::make($storagePath)->response();
    }
}
