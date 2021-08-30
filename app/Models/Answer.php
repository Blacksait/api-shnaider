<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'poll_id', 'true_answer', 'answer_title', 'link', 'textarea', 'textarea'
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class, 'poll_id', 'id');
    }
}
