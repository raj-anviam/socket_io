<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;

class ChatController extends Controller
{
    public function index() {
        if(request()->expectsJson()) {

            return \Auth::user();

        }
        $users = User::where('id', '!=', request()->user()->id)->get();
        return view('home', compact('users'));
        // return view('welcome', compact('users'));
    }
    
    public function conversation($userId) {
        $users = User::where('id', '!=', \Auth::user()->id)->get();
        $receiver = User::findOrFail($userId);
        return view('chat', compact('receiver', 'users'));
    }

    public function sendMessage(Request $request) {

        return \Auth::user();
        
        $message = Message::create([
            'message' => $request->message,
            'sender_id' => \Auth::user()->id,
            'receiver_id' => $request->receiver_id
        ]);

        $message = Message::with('user', 'receiver')->whereId($message->id)->first();

        \App\Events\ReceiveMessage::dispatch($message);
        
        return \Response::json(['data' => $message]);
    }
}
