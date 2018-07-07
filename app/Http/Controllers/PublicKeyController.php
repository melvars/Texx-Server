<?php

namespace App\Http\Controllers;

use App\PublicKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicKeyController extends Controller
{
    public function getUsersKey($user_id)
    {
        $public_key = PublicKeys::select('key')->where('user_id', $user_id)->first();
        // TODO: Friends: do not allow to request every public key

        return $public_key ? $public_key : abort(404, "This public key doesn't exist.");
    }

    public function setUsersKey(Request $request, $user_id)
    {
        $request->validate([
            'key' => 'required|max:4096',
        ]);

        $public_key = PublicKeys::firstOrNew(['user_id' => $user_id]);
        $public_key->user_id = $user_id;
        $public_key->key = $request->input('key');
        $public_key->save();

        return response()->json(['success' => 'Successfully inserted public key.']);
    }
}
