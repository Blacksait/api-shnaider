<?php

namespace App\Repositories;

use App\Models\Speaker;
use Illuminate\Support\Facades\DB;

class SpeakerRepository
{
    protected $speaker;

    public function __construct(Speaker $speaker)
    {
        $this->speaker = $speaker;
    }

    public function getSpeakers()
    {
        return DB::table('speakers')->select()->where('show', 1)->orderBy('sort', 'asc')->get();
    }
}
