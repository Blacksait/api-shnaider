<?php

namespace App\Services;

use App\Models\Stream;
use App\Repositories\StreamRepository;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class StreamService
{
    protected $streamRepository;
    
    /**
     * StreamService constructor.
     * @param $streamRepository
     */
    public function __construct(StreamRepository $streamRepository)
    {
        $this->streamRepository = $streamRepository;
    }
    
    /**
     * @param $stream_id
     * @return mixed
     */
    public function getStreamSetting($stream_id)
    {
        try {
            $stream = $this->streamRepository->getStreamById($stream_id);
            $settings = $stream->toArray();
            $settings['stream_id'] = $stream->id;
            $settings['name'] = $stream->location()->name;

            unset($settings['id']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get result!');
        }
        return $settings;
    }

    /**
     * @param $stream_id
     * @return mixed
     */
    public function getTimeMarks($stream_id)
    {
        try {
            $stream = $this->streamRepository->getStreamById($stream_id);
            $timemarks = $stream->location()->sessions()->where('sessiondate', $stream->date)->whereNotNull('timemark')->pluck('name', 'timemark')->toArray();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get time marks!');
        }
        return $timemarks;
    }
}