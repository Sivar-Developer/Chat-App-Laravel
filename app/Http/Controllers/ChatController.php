<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChatConversation;
use App\Models\ChatParticipant;
use App\Models\ChatMessage;

class ChatController extends Controller
{
    public function conversations()
    {
        $conversations = auth('api')->user()->chatParticipants()->with('chatConversation.chatMessages', function($query) {
            return $query->latest()->first();
        })->get();

        return response()->json($conversations, 200);
    }

    public function storeMessage(Request $request)
    {

    }
}
