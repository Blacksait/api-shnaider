<?php

namespace App\Http\Controllers;

use App\Services\MeetingService;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    protected $meetingService;

    /**
     * MeetingController constructor.
     * @param $meetingService
     */
    public function __construct(MeetingService $meetingService)
    {
        $this->middleware('auth:api', ['except' => ['index', 'update', 'destroy', 'setMeeting']]);
        $this->meetingService = $meetingService;
    }

    public function getMeeting()
    {
        try {
            $result = $this->meetingService->getMeeting();
            $result['data'] = $result;
            $result['status'] = 200;
        } catch (\Exception $e) {
            $result = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return response()->json($result['data'], $result['status']);
    }

    public function index()
    {
        $result = $this->meetingService->all();
        return view('meeting', compact('result'));
    }

    public function update(Request $request)
    {
        try {
            $result['data'] = $this->meetingService->updateMeeting($request->only(['id', 'confirm', 'user_id', 'role']));
            $result['status'] = 200;
        } catch (\Exception $e) {
            $result = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return response()->json($result['data'], $result['status']);
    }

    public function destroy(Request $request)
    {
        try {
            $result = $this->meetingService->deleteMeeting($request->only(['id']));
            $result['data'] = $result;
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
