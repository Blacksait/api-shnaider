<?php

namespace App\Services;

use App\Models\Location;
use App\Models\Stream;
use App\Repositories\SessionRepository;
use Illuminate\Support\Facades\DB;


class SessionService
{
    protected $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    public function getSessions()//TODO
    {
        $sessions = $this->sessionRepository->getSessions();
        $result = [
            [],
            [],
            []
        ];
        foreach ($sessions as $key => $session) {
            if ($session->location_id == '200270528') continue;
            $names = [];
            
            $location_name = Location::where('location_id',$session->location_id)->get()->first()->name;
            $streamId = Stream::where(['location_id' => $session->location_id, 'date' => $session->sessiondate])->get()->first()->id;
            $explode_speakers = explode(';' ,$session->speaker_ids);

            DB::table('speakers')->whereIn('speaker_id', $explode_speakers)->get()->each(function ($speaker_name) use (&$names){
                $names[] = $speaker_name->name;
            });

            $start_time_ex = substr($session->starttime, 0, -3);
            $end_time_ex = substr($session->endtime, 0, -3);
            switch ($session->sessiondate) {
                case '2021-04-27':
                    $result[0][] = [
                        'title' => $session->name,
                        'time' => $start_time_ex . ' - ' . $end_time_ex,
                        'location' => $location_name,
                        'speakers' => implode(", " , $names),
                        'stream_id' => $streamId,
                        'timemarker' => $session->timemark
                    ];
                    break;
                case '2021-04-28':
                    $result[1][] = [
                        'title' => $session->name,
                        'time' => $start_time_ex . ' - ' . $end_time_ex,
                        'location' => $location_name,
                        'speakers' => implode(", " , $names),
                        'stream_id' => $streamId,
                        'timemarker' => $session->timemark
                    ];
                    break;
                case '2021-04-29':
                    $result[2][] = [
                        'title' => $session->name,
                        'time' => $start_time_ex . ' - ' . $end_time_ex,
                        'location' => $location_name,
                        'speakers' => implode(", " , $names),
                        'stream_id' => $streamId,
                        'timemarker' => $session->timemark
                    ];
                    break;
            }
        }
        return $result;
    }

    /**
     * Get Personal User Schedule
     * @return $data
     */
    public function getSessionsPersonal()
    {//todo Repository
        $data = [
            [],
            [],
            []
        ];
        $sessions = $this->sessionRepository->getSessionsPersonal();
        foreach ($sessions as $key => $session) {
            $names = [];

            $location_name = Location::where('location_id',$session->location_id)->get()->first()->name;
            $streamId = Stream::where(['location_id' => $session->location_id, 'date' => $session->sessiondate])->get()->first()->id;
            $explode_speakers = explode(';' ,$session->speaker_ids);

            DB::table('speakers')->whereIn('speaker_id', $explode_speakers)->get()->each(function ($speaker_name) use (&$names){
                $names[] = $speaker_name->name;
            });

            $start_time_ex = substr($session->starttime, 0, -3);
            $end_time_ex = substr($session->endtime, 0, -3);

            switch ($session->sessiondate) {
                case '2021-04-27':
                    $data[0][] = [
                        'title' => $session->name,
                        'time' => $start_time_ex . ' - ' . $end_time_ex,
                        'location' => $location_name,
                        'speakers' => implode(", " , $names),
                        'stream_id' => $streamId
                    ];
                    break;
                case '2021-04-28':
                    $data[1][] = [
                        'title' => $session->name,
                        'time' => $start_time_ex . ' - ' . $end_time_ex,
                        'location' => $location_name,
                        'speakers' => implode(", " , $names),
                        'stream_id' => $streamId
                    ];
                    break;
                case '2021-04-29':
                    $data[2][] = [
                        'title' => $session->name,
                        'time' => $start_time_ex . ' - ' . $end_time_ex,
                        'location' => $location_name,
                        'speakers' => implode(", " , $names),
                        'stream_id' => $streamId
                    ];
                    break;
            }
        }
        return $data;
    }
}
