<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'meeting_time', 'speakers_id', 'user_id', 'meeting_confirm', 'meeting_date'
    ];

    public $timestamps = false;
    public $table = 'meeting';
    protected $primaryKey = 'id';

    public function speaker()
    {
        return $this->hasOne(Speaker::class, 'speaker_id', 'speakers_id')->first();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'attendee_id', 'user_id')->first();
    }
}
