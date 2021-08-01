<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function chatConversation()
    {
        return $this->belongsTo(ChatConversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
