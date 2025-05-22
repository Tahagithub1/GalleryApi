<?php

namespace App\Models\Message;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['user_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
