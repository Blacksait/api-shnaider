<?php

namespace App\Repositories;

use App\Models\Stream;

class StreamRepository
{
    protected $stream;

    /**
     * StreamRepository constructor.
     * @param Stream $stream
     */
    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }

    /**
     * @param $stream_id
     * @return mixed
     */
    public function getStreamById($stream_id)
    {
        return $this->stream::find($stream_id);
    }
}
