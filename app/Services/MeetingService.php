<?php

namespace App\Services;

use App\Repositories\MeetingRepository;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class MeetingService
{
    protected $meetingRepository;

    /**
     * MeetingService constructor.
     * @param $meetingRepository
     */
    public function __construct(MeetingRepository $meetingRepository)
    {
        $this->meetingRepository = $meetingRepository;
    }

    public function getMeeting()
    {
        try {
            $result = [];
            $meetings = $this->meetingRepository->getMeeting();
            foreach ($meetings as $meeting) {
                $speaker = $meeting->speaker();

                if(!isset($result[$speaker->speaker_id]['expert']))
                    $result[$speaker->speaker_id]['expert'] = $speaker->toArray();

                $result[$speaker->speaker_id]['slots'][$meeting->meeting_date][] = [
                    'id' => (int) $meeting->id,
                    'time' => $meeting->meeting_time,
                    'confirm' => (bool) $meeting->meeting_confirm
                ];
            }
            $result = array_values($result);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get meeting');
        }

        return $result;
    }

    public function all()
    {
        try {
            $result = $this->meetingRepository->getMeeting('meeting_confirm', 'desc');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get meeting');
        }

        return $result;
    }

    public function updateMeeting($data)
    {
        try {//todo validate
            $isUpdate = $this->meetingRepository->updateMeeting($data);
            $meetings = $this->meetingRepository->getMeeting();
            $result = [];
            foreach ($meetings as $meeting) {
                $speaker = $meeting->speaker();

                if(!isset($result['data'][$speaker->speaker_id]['expert']))
                    $result['data'][$speaker->speaker_id]['expert'] = $speaker->toArray();

                $result['data'][$speaker->speaker_id]['slots'][$meeting->meeting_date][] = [
                    'id' => (int) $meeting->id,
                    'time' => $meeting->meeting_time,
                    'confirm' => (bool) $meeting->meeting_confirm
                ];
            }
            $result['data'] = array_values($result['data']);
            $result['status'] = $isUpdate ? 'success' : 'error';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable update meeting '.$e->getMessage());
        }
        return $result; 
    }

    public function deleteMeeting($data)
    {
        try {
            $result = $this->meetingRepository->deleteMeeting($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable delete meeting');
        }
        return $result;
    }
}
