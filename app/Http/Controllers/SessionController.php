<?php

namespace App\Http\Controllers;

use App\Services\SessionService;

class SessionController extends Controller
{
    protected $sessionService;

    public function __construct(SessionService $sessionService)
    {
        $this->middleware('auth:api', ['except' => ['all']]);
        $this->sessionService = $sessionService;
    }

    public function all()
    {
        try {
            $res['data'] = $this->sessionService->getSessions();
            $res['status'] = 200;
        } catch (\Exception $e) {
            $res = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }

        return response()->json($res['data'], $res['status']);
    }

    public function allPersonal()
    {
        try {
            $res['data'] = $this->sessionService->getSessionsPersonal();
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
