<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['user_id','date', 'time',"description"];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
