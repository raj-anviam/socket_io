<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
    
    public function receiver() {
        return $this->belongsToMany(User::class, 'message_user', 'message_id', 'receiver_id')->withTimestamps();
    }
}
