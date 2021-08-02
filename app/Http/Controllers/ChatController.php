<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChatConversation;
use App\Models\ChatParticipant;
use App\Models\ChatMessage;
use Carbon\Carbon;

class ChatController extends Controller
{
    public function conversations()
    {
        $chatConversationIds = ChatParticipant::where('user_id', auth('api')->id())->get()->pluck('chat_conversation_id');
        $chatConversations = ChatConversation::with('chatMessages')->with('chatParticipants.user')->whereIn('id', $chatConversationIds)->orderBy('updated_at', 'desc')->get();

        return response()->json($chatConversations, 200);
    }

    public function conversation($chat_conversation)
    {
        $chatMessages = ChatMessage::with('sender')->where('chat_conversation_id', $chat_conversation)->oldest()->get();

        return response()->json($chatMessages, 200);
    }

    public function conversationWithUser($user_id)
    {
        if($user_id) {
            $chatConversationIds = ChatParticipant::where('user_id', auth('api')->id())->get()->pluck('chat_conversation_id');
            $chatConversations = ChatConversation::with('chatParticipants.user')->whereIn('id', $chatConversationIds)->get();
            $chatConversationId = null;
            foreach($chatConversations as $chatConversation) {
                foreach($chatConversation['chatParticipants'] as $participant) {
                    if($participant['user_id'] == $user_id) {
                        $chatConversationId = $chatConversation->id;
                    }
                }
            }
    
            return $this->conversation($chatConversationId);
        } else {
            return response()->json(null, 404);
        }
        
    }

    public function storeMessage(Request $request)
    {
        $chatConversation = ChatConversation::updateOrCreate(['id' => request('chat_conversation_id')],['creator_id' => auth('api')->id(), 'updated_at' => Carbon::now()]);

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
