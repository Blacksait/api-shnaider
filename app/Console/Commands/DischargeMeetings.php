<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DischargeMeetings extends Command
{
    protected $meeting;

    public function __construct(Meeting $meeting)
    {
        parent::__construct();
        $this->meeting = $meeting;
    }

    protected $name = 'discharge:meetings';
    protected $description = "Discharge meetings into API Shnaider and into DB";

    public function handle()
    {
        try {
            $meetings = DB::table('meeting')->where('meeting_time', '<', Carbon::now())->update(['meeting_confirm' => 0]);
            echo 'Complete! Meetings successfully discharge.' . PHP_EOL;
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
