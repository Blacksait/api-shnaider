<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'attendee_id', 'action_id'
    ];

    /**
     * Hide timestamps
     * @var bool
     */
    public $timestamps = true;

    /**
     * Table
     * @var string
     */
    public $table = 'user_activities';

    /**
     * Primary key
     * @var integer
     */
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->hasOne(User::class, 'attendee_id', 'attendee_id')->first();
    }

    public function action()
    {
        return $this->hasOne(Action::class, 'id', 'action_id')->first();
    }
}