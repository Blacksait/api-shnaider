<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_id', 'videoPath', 'videoPathEn', 'chat_id', 'chatWidget_id', 'date'
    ];
    
    /**
     * Table
     * @var string
     */
    public $table = 'streams';
    public $primaryKey = 'id';
    
    public function location()
    {
        return $this->hasOne(Location::class, 'location_id', 'location_id')->first();
    }
}
