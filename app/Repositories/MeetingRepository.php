<?php

namespace App\Repositories;

use App\Models\Action;
use App\Models\Meeting;
use App\Models\UserActivity;

class MeetingRepository
{
    protected $meeting;

    /**
     * MeetingRepository constructor.
     * @param $meeting
     */
    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    public function getMeeting($sort = 'meeting_time', $order = 'asc')
    {
        $result = $this->meeting::orderBy($sort, $order)->get();
        return $result;
    }

    public function updateMeeting($data)
    {//todo
        $meeting = $this->meeting::find($data['id']);

        if (!isset($data['role']))
            $data['role'] = 'user';

        if((string) $data['role'] !== 'moderator'){
            if ((int) $meeting->meeting_confirm > 0)//todo || (!isset($data['role']) && (string) $data['role'] !== 'moderator')
                return false;

            $meeting->meeting_confirm = (int) $data['confirm'];
            $meeting->user_id = (int) $data['user_id'];
            $meeting->save();

            return true;
        } else {
            $meeting->meeting_confirm = (int) $data['confirm'];
            $meeting->user_id = (int) $data['user_id'];
            $meeting->save();

            if((int) $data['confirm'] === 1) {
                $scoreRepository = new ScoreRepository(new Action());
                $scoreRepository->storeActivity([
                    'title' => 'meeting',
                    'attendee_id' => (int) $data['user_id']
                ]);
            }

            return true;
        }
    }

    public function deleteMeeting($data)
    {
        return $this->meeting::destroy($data['id']);
    }

}
