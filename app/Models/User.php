<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'avatarImageUrl',
    ];

    public function getAvatarImageUrlAttribute() {
        return 'https://ui-avatars.com/api/?name='.$this->name.'&color=7F9CF5&background=EBF4FF&size=500';
    }

    public function chatConversations()
    {
        return $this->hasMany(ChatConversation::class, 'creator_id');
    }

    public function chatParticipants()
    {
        return $this->hasMany(ChatParticipant::class, 'user_id');
    }
}
