<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'session_id'
    ];

    /**
     * Hide timestamps
     * @var bool
     */
    public $timestamps = false;

    /**
     * Table
     * @var string
     */
    public $table = 'user_sessionlist';
}

