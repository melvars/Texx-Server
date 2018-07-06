<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PublicKeyController extends Controller
{
    public function getUsersKey($user_id)
    {
        $public_key = DB::table('public_keys')
            ->where('user_id', $user_id)
            ->value('key'); 
            // TODO: Friends: do not allow to request every public key
        return $public_key ? $public_key : abort(404);
    }
}
