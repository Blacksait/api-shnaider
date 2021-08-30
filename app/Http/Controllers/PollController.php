<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Services\PollService;

class PollController extends Controller
{
    protected $pollService;

    /**
     * PollResultController constructor.
     * @param $pollService
     */
    public function __construct(PollService $pollService)
    {
        $this->middleware('auth:api',['except' => ['index']]);
        $this->pollService = $pollService;
    }

    public function index()
    {
        $locations = Location::all();
        return view('rooms', compact('locations'));
    }

    /**
     * Get all polls
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        try {
            $result = $this->pollService->allPolls();
            $res['data'] = $result;
            $res['status'] = 200;
        } catch (\Exception $e) {
            $res = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }

        return response()->json($res['data'], $res['status']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeUserAnswer(Request $request)
    {
        try {
            $data = $request->only(['poll_id', 'answer_id', 'answer_text']);
            $result = $this->pollService->storeUserAnswer($data);

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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeUserEvaluation(Request $request)
    {
        try {
            $data = $request->only(['stream_id', 'text', 'rating']);

            if($this->pollService->storeUserEvaluation($data)) {
                $result['status'] = 200;
                $result['data']['data'] = 'Data successfully entered!';
                $result['data']['status'] = 'success';
            }
            else{
                $result['status'] = 500;
                $result['data']['data'] = 'Unable store evaluation';
                $result['data']['status'] = 'error';
            }
        } catch (\Exception $e) {
            $result = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return response()->json($result['data'], $result['status']);
    }

    /**
     * @param $poll_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPollResult($poll_id)
    {
        try {
            $pollResult['data'] = $this->pollService->getPoll($poll_id);// test
            $pollResult['status'] = 200;
        } catch (\Exception $e) {
            $pollResult = [
                'data' => $e->getMessage(),
                'status' => 500
            ];
        }
        return response()->json($pollResult['data'], $pollResult['status']);
    }
}
