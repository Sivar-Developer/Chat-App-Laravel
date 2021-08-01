<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChatConversation;
use App\Models\ChatParticipant;
use App\Models\ChatMessage;

class ChatController extends Controller
{
    public function conversaions()
    {
        $conversations = auth('api')->user()->participants()->conversation()->with('messages', function($query) {
            return $query->latest()->first();
        })->get();

        return response()->json($conversations, 200);
    }

    public function storeMessage(Request $request)
    {

    }
}
