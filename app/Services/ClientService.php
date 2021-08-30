<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserSession;
use App\Repositories\ClientRepository;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class ClientService
{
    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var ClientRepository
     */
    protected $token, $token2;

    /**
     * ClientService constructor.
     * @param ClientRepository $clientRepository
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->token = $this->getToken();
    }

    /**
     * Get token from client
     * @return null|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getToken()
    {
        try {
            $result = $this->clientRepository->getToken();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get token');
        }

        return $result;
    }

    /**
     * Get token from client
     * @return null|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTokenV2()
    {
        try {
            $result = $this->clientRepository->getTokenV2();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get token');
        }

        return $result;
    }

    /**
     * Get users
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUsers()
    {
        try{
            $result = $this->clientRepository->getUsers($this->token);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get users');
        }

        return $result;
    }

    /**
     * Get Session list
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSessionList()
    {
        try{
            $result = $this->clientRepository->getSessionList($this->token);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get session list');
        }

        return $result;
    }

    /**
     * Get Session Personal list
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSessionPersonalList()
    {
        try{
            $result = $this->clientRepository->getSessionPersonalList($this->token);
//            dd(count($result));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get session personal list');
        }

        return $result;
    }

    /**
     * Get speakers
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSpeakers()
    {
        try {
            $result = $this->clientRepository->getSpeakers($this->token);

            if(empty($result))
                throw new InvalidArgumentException('Speakers list is empty');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get speakers list');
        }

        return $result;
    }

    /**
     * Set User data
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function setUserData($id)
    {
        try{
            $user = $this->clientRepository->getUserData($this->token, $id);
            $result = User::updateOrCreate( //todo to Repository
                ['attendee_id' => $user['attendeeid']], [
                    'password' => app('hash')->make($user['attendeeid']),
                    'fname' => $user['fname'],
                    'mname' => $user['mname'],
                    'lname' => $user['lname'],
                    'email' => $user['email'],
                    'mphone' => $user['mphone'],
                    'city' => $user['city'],
                    'company' => $user['company']
                ]);
            $sessionList = $this->clientRepository->getSessionPersonalListById($this->token, $id);
            foreach ($sessionList as $session) {
                UserSession::create([
                    'user_id' => $session->attendeeid,
                    'session_id' => $session->sessionid
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable set user data');
        }

        return $result;
    }

    /**
     * Forming a parameter value 'auth'
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAuthHCC($data)
    {
        try{
            $time        = time();
            $secret      = "Proofix444secretkey"; // Секретный ключ, который Вы ввели в административной панели сайта.
            $user_base64 = base64_encode( json_encode([
                'nick' => $data['fname'].' '.$data['lname'],
                'email' => $data['email'],
                'id' => $data['attendee_id']
            ]));
            $sign        = md5($secret . $user_base64 . $time);
            $auth        = $user_base64 . "_" . $time . "_" . $sign;
        } catch (\Exception $e) {
            $auth = null;
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable set user data');
        }

        return $auth;
    }
}
