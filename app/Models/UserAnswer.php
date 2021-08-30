<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    //как оказалось это дубль PollResult (facepalm)
    protected $fillable = [
        'message', 'user_id', 'answer_id', 'poll_id', 'time'
    ];

    /**
     * Hide timestamps
     * @var bool
     */
    public $timestamps = false;

    public function User()
    {
        return $this->belongsTo(User::class,'users_id','attendee_id');
    }

    public function Answer()
    {
        return $this->belongsTo(Answer::class,'answer_id','id');
    }

    public function poll()
    {
        return $this->belongsTo(Poll::class,'poll_id','id');
    }
}
