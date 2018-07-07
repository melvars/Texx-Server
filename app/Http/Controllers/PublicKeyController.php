<?php

namespace App\Http\Controllers;

use App\PublicKeys;
use Auth;
use Cookie;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class PublicKeyController extends Controller
{
    public function getUsersKey($user_id)
    {
        $public_key = PublicKeys::select('key')->where('user_id', $user_id)->first();
        // TODO: Friends: do not allow to request every public key

        return $public_key ? $public_key : abort(404, "This public key doesn't exist.");
    }

    public function setUsersKey(Request $request)
    {
        $request->validate([
            'key' => 'required|max:4096',
        ]);

        $public_key = PublicKeys::firstOrNew(['user_id' => $user_id]);
        $public_key->user_id = $user_id;
        $public_key->key = $request->input('key');
        $public_key->save();

        return response()->json(['message' => 'Successfully inserted public key.']);
    }

    public function setUsersKeyByCookie(Request $request)
    {
        if ($_COOKIE['publickey'] !== null) {
            $user = Auth::user();
            $public_key = PublicKeys::firstOrNew(['user_id' => $user->id]);
            $public_key->user_id = $user->id;
            $public_key->key = $_COOKIE['publickey'];
            $public_key->save();

            return redirect('/');
        } else {
            abort(400, "Public key is malformed.");
        }
    }
}
