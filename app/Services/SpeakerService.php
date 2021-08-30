<?php
namespace App\Services;

use App\Repositories\SpeakerRepository;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class SpeakerService
{
    protected $speakerRepository;

    public function __construct(SpeakerRepository $speakerRepository)
    {
        $this->speakerRepository = $speakerRepository;
    }

    public function getSpeakers()
    {
        try {
            $result = $this->speakerRepository->getSpeakers();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get speakers');
        }

        return $result;
    }
}
