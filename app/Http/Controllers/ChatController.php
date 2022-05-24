<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;

class ChatController extends Controller
{
    public function index() {
        $users = User::where('id', '!=', \Auth::user()->id)->get();
        return view('welcome', compact('users'));
    }
    
    public function conversation($userId) {
        $users = User::where('id', '!=', \Auth::user()->id)->get();
        $receiver = User::findOrFail($userId);
        return view('chat', compact('receiver', 'users'));
    }

    public function sendMessage(Request $request) {
        $message = Message::create([
            'message' => $request->message
        ]);

        $message->user()->attach(\Auth::user()->id, ['receiver_id' => $request->receiver_id]);

        $message = Message::with('user', 'receiver')->whereId($message->id)->first();
        
        return \Response::json(['data' => $message]);
    }
}
