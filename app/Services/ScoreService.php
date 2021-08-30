<?php
namespace App\Services;

use App\Repositories\ScoreRepository;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ScoreService
{
    protected $scoreRepository;

    public function __construct(ScoreRepository $scoreRepository)
    {
        $this->scoreRepository = $scoreRepository;
    }

    public function allActions()
    {
        try {
            $result = $this->scoreRepository->allActions();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get actions');
        }

        return $result;
    }

    public function updateAction($data)
    {
        try {
            $validate = Validator::make($data,[
                'id' => 'required|exists:actions,id',
                'score_correct' => 'required|integer|numeric',
                'score_wrong' => 'required|integer|numeric'
            ]);

            if($validate->fails())
                throw new InvalidArgumentException($validate->errors());

            $result = $this->scoreRepository->updateAction($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable update action');
        }

        return $result;
    }

    public function storeActivity($data)
    {
        try {
            $validate = Validator::make($data,[
                'title' => 'required|exists:actions,title',
                'attendee_id' => 'required|exists:users,attendee_id'
            ]);

            if($validate->fails())
                throw new InvalidArgumentException($validate->errors());

            $result = $this->scoreRepository->storeActivity($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable store user activity');
        }

        return $result;
    }

    public function getRating()
    {
        try {
            $result = $this->scoreRepository->getRating();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get rating');
        }

        return $result;
    }

    public function getUserScore()
    {
        try {
            $result = $this->scoreRepository->getUserScore();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get user score');
        }

        return $result;
    }
}
