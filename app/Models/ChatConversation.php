<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class)->latest();
    }

    public function chatParticipants()
    {
        return $this->hasMany(ChatParticipant::class);
    }
}
