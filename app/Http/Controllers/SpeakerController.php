<?php

namespace App\Http\Controllers;

use App\Services\SpeakerService;

class SpeakerController extends Controller
{
    protected $speakerService;

    public function __construct(SpeakerService $speakerService)
    {
        $this->middleware('auth:api', ['except' => ['all']]);
        $this->speakerService = $speakerService;
    }

    public function all()
    {
        try {
            $res['data'] =  $this->speakerService->getSpeakers();
            $res['status'] = 200;
        }
        catch (\Exception $e){
            $res = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }

        return response()->json($res['data'], $res['status']);
    }
}
