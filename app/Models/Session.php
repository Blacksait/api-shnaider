<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'session_id',
        'speaker_ids',
        'sort',
        'name',
        'sessiondate',
        'starttime',
        'endtime',
        'location_id'
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
    public $table = 'session_list';

    /**
     * Primary key
     * @var integer
     */
    protected $primaryKey = 'session_id';

    public function users()
    {
        return $this->belongsToMany(User::class,'user_sessionlist','session_id','user_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class,'location_id', 'location_id');
    }
}
