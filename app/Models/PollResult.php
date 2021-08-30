<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollResult extends Model
{
    protected $fillable = [
        'id', 'user_id', 'answer_id', 'poll_id', 'time', 'message'
    ];

    public $timestamps = false;
    public $table = 'user_answers';
    protected $primaryKey = 'id';

    public function poll()
    {
        return $this->hasOne(Poll::class, 'id', 'poll_id')->first();
    }

    public function answer()
    {
        return $this->hasOne(Answer::class, 'id', 'answer_id')->first();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'attendee_id', 'user_id')->first();
    }
}
