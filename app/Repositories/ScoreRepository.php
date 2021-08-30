<?php

namespace App\Repositories;

use App\Models\Action;
use App\Models\UserActivity;

class ScoreRepository
{
    protected $action;

    public function __construct(Action $actions)
    {
        $this->action = $actions;
    }

    public function allActions()
    {
        return $this->action::all();
    }

    public function updateAction($data)
    {
        $action = $this->action::find($data['id']);
        $action->score_correct = $data['score_correct'];
        $action->score_wrong = $data['score_wrong'];
        $action->save();

        return $action;
    }

    public function storeActivity($data)
    {
        $action = $this->action::where('title', $data['title'])->get()->first();

        if(UserActivity::where(['action_id' => $action->id, 'attendee_id' => $data['attendee_id']])->get()->first() && !in_array($action->id, [4, 5, 7, 9]))
            return 'Data already entered!';

        $userActivity = new UserActivity();
        $userActivity->action_id = $action->id;
        $userActivity->attendee_id = $data['attendee_id'];
        $userActivity->save();

        return 'Successfully set user activity!';
    }

    public function getRating()
    {
        try {
            $result = [];
            $usersActivities = UserActivity::all();
            foreach ($usersActivities as $activity) {
                $user = $activity->user()->makeVisible('password')->toArray();
                if($user['company'] === 'Schneider Electric' || $user['fname'] == null)
                    continue;

                if((bool) $user['confirmShowName']) {
                    $result[$user['attendee_id']]['firstName'] = $user['fname'];
                    $result[$user['attendee_id']]['lastName'] = $user['lname'];
                }
                else {
                    $result[$user['attendee_id']]['firstName'] = 'User';
                    $result[$user['attendee_id']]['lastName'] = substr(preg_replace('%[^A-Za-z0-9]%', null, $user['password']), -5);
                }

                $action = $activity->action();

                if(isset($result[$user['attendee_id']]['points']))
                    $result[$user['attendee_id']]['points'] = $result[$user['attendee_id']]['points'] + $action->score_correct;//todo Formule + $action->score_wrong
                else
                    $result[$user['attendee_id']]['points'] = 0 + $action->score_correct + $action->score_wrong;
            }
            $result = array_slice($result,0,50);
            array_multisort(array_column($result, 'points'), SORT_DESC,  $result);

        } catch (\Exception $e){
            throw new \InvalidArgumentException($e->getMessage());
        }
        return $result;
    }

    public function getUserScore($score = 0)
    {
        try {
            $activities = UserActivity::where('attendee_id', auth()->user()->attendee_id)->get();
            foreach ($activities as $activity){
                $action = $activity->action();
                $score += $action->score_correct;//todo formule
            }
        } catch (\Exception $e){
            throw new \InvalidArgumentException($e->getMessage());
        }
        return compact('score');
    }
}
