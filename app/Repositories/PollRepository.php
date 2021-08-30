<?php

namespace App\Repositories;

use App\Models\Answer;
use App\Models\Location;
use App\Models\Poll;
use App\Models\PollResult;
use App\Models\Stream;
use App\Models\UserAnswer;

class PollRepository
{
    protected $pollResult,$poll;

    /**
     * PollRepository constructor.
     * @param $pollResult
     */
    public function __construct(PollResult $pollResult, Poll $poll)
    {
        $this->pollResult = $pollResult;
        $this->poll = $poll;
    }

    public function allPolls()
    {
        return $this->poll::all();
    }

    /**
     * @param $poll_id
     * @return mixed
     */
    public function getPollResult($poll_id)
    {
        $pollResults = $this->pollResult::where('poll_id', $poll_id)->get();
        return $pollResults;
    }

    /**
     * @param $data
     * @return PollResult|null
     */
    public function storeUserAnswer($data)
    {
        try {
            foreach ($data['answer_id'] as $answer_id) {
                $pollResult = new PollResult();
                $pollResult->user_id = auth()->user()->attendee_id;
                $pollResult->answer_id = $answer_id;
                $pollResult->poll_id = $data['poll_id'];
                $pollResult->save();

                $result = $pollResult->fresh();
            }
        } catch (\Exception $e){
            throw new \InvalidArgumentException($e->getMessage());
        }
        return $result;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function storeUserEvaluation($data)
    {
        try {
            $location_name = Stream::where('id', $data['stream_id'])->get()->first()->location()->name;
            $user = auth()->user();

            $client = new \Google_Client();
            $client->setApplicationName("shnaider");
            $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
            $client->setAuthConfig(base_path() . '/public/MyProject-098ec42a6a12.json');
            $client->setAccessToken("098ec42a6a1281836f35611ccdd30f85948dafe6");

            $service = new \Google_Service_Sheets($client);

            $options = array('valueInputOption' => 'RAW');
            $values = [[date("d.m.y H:i:s"), $data['rating'], $data['text'], $user->attendee_id, $user->fname, $user->lname, $user->email, $user->mphone]];
            $body = new \Google_Service_Sheets_ValueRange(['values' => $values]);

            $result = $service->spreadsheets_values->append("1E5BC5vw4TsrPe3jtXrf7NqzGncL5nnBCJX03-NCzX38", $location_name.'!A1:H1', $body, $options);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        return $result;
    }

    /**
     * Запись ответа на открытый вопрос
     * @param $data
     * @return mixed
     */
    public function storeUserAnswerOpen($data)
    {
        try {
            $pollResult = new PollResult();
            $pollResult->user_id = auth()->user()->attendee_id;
            $pollResult->message = $data['answer_text'];
            $pollResult->poll_id = $data['poll_id'];
            $pollResult->answer_id = isset($data['answer_id'][0]) && !empty($data['answer_id'][0]) ? $data['answer_id'][0] : 355;
            $pollResult->save();

            $result = $pollResult->fresh();
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        return $result;
    }

    /**
     * @param $poll_id
     * @return bool
     */
    public function isQuiz($poll_id)
    {
        $isQuiz = false;

        Poll::find($poll_id)->answers->each(function ($element) use (&$isQuiz){
            if ((int) $element->true_answer === 1) {
                $isQuiz = !$isQuiz;
                return false;
            }
        });

        return (bool) $isQuiz;
    }

    /**
     * is Open Question
     * @param $poll_id
     * @return bool
     */
    public function isOpenQuestion($poll_id)
    {
        return (bool) Poll::find($poll_id)->getMessage;
    }

    public function countCorrectUserAnswers($poll_id)
    {
        //уже впадлу думать и делать красиво, лишь бы работало
        try {
            $count = 0;
            $userAnswers = UserAnswer::where('poll_id', $poll_id)->get();
            $correctAnswers = Answer::where(['poll_id' => $poll_id, 'true_answer' => 1])->get();
            foreach ($userAnswers as $userAnswer) {
                foreach ($correctAnswers as $correctAnswer) {
                    if($userAnswer->answer_id === $correctAnswer->id)
                        $count++;
                }
            }
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        return $count;
    }
}
