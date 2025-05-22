<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return response()->json(['message' => 'پیام ثبت شد', 'data' => $message]);
    }
}
