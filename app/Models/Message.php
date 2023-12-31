<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'conversation_number','sender_id', 'recipient_id', 'subject', 'content'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
    
    // public function latestMessage()
    // {
    //     return Message::where(function ($query) {
    //         $query->where('sender_id', $this->sender_id)
    //             ->where('recipient_id', $this->recipient_id)
    //             ->orWhere('sender_id', $this->recipient_id)
    //             ->where('recipient_id', $this->sender_id);
    //     })->latest('created_at')->first();
    // }
    // public function latestMessage()
    // {
    //     return Message::where(function ($query) {
    //         $query->where('sender_id', $this->sender_id)
    //             ->where('recipient_id', $this->recipient_id)
    //             ->orWhere('sender_id', $this->recipient_id)
    //             ->where('recipient_id', $this->sender_id);
    //     })->latest('created_at')->first();
    // }
    public function latestMessage()
    {
        return $this->hasOne(Message::class, 'conversation_number', 'conversation_number')
            ->latest('created_at');
    }



    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_number', 'conversation_number');
    }
    

}
