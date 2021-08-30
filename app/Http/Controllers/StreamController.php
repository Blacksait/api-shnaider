<?php

namespace App\Http\Controllers;

use App\Services\StreamService;

class StreamController extends Controller
{
    protected $streamService;
    
    /**
     * StreamController constructor.
     * @param $streamService
     */
    public function __construct(StreamService $streamService)
    {
        $this->middleware('auth:api', ['except' => ['getTimeMarks']]);
        $this->streamService = $streamService;
    }
    
    /**
     * Get settings by stream id
     * @param $stream_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStreamSettings($stream_id)
    {
        try {
            $result['data'] = $this->streamService->getStreamSetting($stream_id);
            $result['status'] = 200;
        } catch (\Exception $e) {
            $result = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return response()->json($result['data'], $result['status']);
    }

    /**
     * Get time marks by stream id
     * @param $stream_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimeMarks($stream_id)
    {
        try {
            $result['data'] = $this->streamService->getTimeMarks($stream_id);
            $result['status'] = 200;
        } catch (\Exception $e) {
            $result = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return response()->json($result['data'], $result['status']);
    }
}
