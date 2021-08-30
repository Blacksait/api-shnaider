<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'score_correct', 'score_wrong', 'title'
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
    public $table = 'actions';

    /**
     * Primary key
     * @var integer
     */
    protected $primaryKey = 'id';
}