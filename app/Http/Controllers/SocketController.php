<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Message;
use Auth;
use Illuminate\Http\Request;
use LRedis;

class SocketController extends Controller
{
    //Write Message
    public function writeMessage()
    {
        $messages = Message::leftJoin('users', function ($join) {
            $join->on('messages.user_id', '=', 'users.id');
            })
            ->select('users.name', 'messages.message')->orderBy('messages.created_at')
            ->get();

        return view('writeMessage', compact('messages'));
    }

    //Send Message
    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        $input = $request->all();
        $redis = LRedis::connection();

        if (!isset($input['message']) || trim($input['message']) === '') {
        } else {
            Message::create([
                'user_id' => $user->id,
                'message' => $input['message'],
            ]);

            $data = ['message' => $input['message'], 'user' => $user->name];
            $redis->publish('message', json_encode($data));
        }
    }
}
