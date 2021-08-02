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
        $chatConversationIds = ChatParticipant::where('user_id', auth('api')->id())->get()->pluck('chat_conversation_id');
        $chatConversations = ChatConversation::with('chatMessages')->with('chatParticipants.user')->whereIn('id', $chatConversationIds)->get();

        return response()->json($chatConversations, 200);
    }

    public function conversation($chat_conversation)
    {
        $chatMessages = ChatMessage::with('sender')->where('chat_conversation_id', $chat_conversation)->latest()->get();

        return response()->json($chatMessages, 200);
    }

    public function storeMessage(Request $request)
    {
        $chatConversation = ChatConversation::updateOrCreate(['id' => request('chat_conversation_id')],['creator_id' => auth('api')->id()]);

        $chatConversation->chatParticipants()->updateOrCreate(['user_id' => request('chat_participant_id')]);
        $chatConversation->chatParticipants()->updateOrCreate(['user_id' => auth('api')->id()]);

        ChatMessage::create([
            'sender_id' => auth('api')->id(),
            'chat_conversation_id' => $chatConversation->id,
            'body' => request('message')
        ]);

        return response()->json(null, 201);
    }

    public function users()
    {
        $users = User::all();

        return response()->json($users, 200);
    }
}
