<?php

namespace App\Repositories;

use GuzzleHttp\Client;

class ClientRepository
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * ClientRepository constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get token
     * @return null|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getToken()
    {
        try{
            $response = $this->client->request('GET', 'https://api-emea.eventscloud.com/api/ds/v1/authenticate?accountid='.env('ACCOUNT_ID').'&key=' . env('API_KEY'));
            $token = json_decode($response->getBody())->accesstoken;
        }catch (\Exception $e) {
            $token = null;
        }

        return $token;
    }

    /**
     * Get token V2
     * @return null|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTokenV2()
    {
        try{
            $response = $this->client->request('POST', 'https://eu.eventscloud.com/api/v2/global/authorize.json',[
                'form_params' => [
                    'accountid' => env('ACCOUNT_ID'),
                    'key' => env('API_KEY')
                ]
            ]);
            $token = json_decode($response->getBody())->accesstoken;
        }catch (\Exception $e) {
            $token = null;
        }

        return $token;
    }

    /**
     * Get users
     * @param $token
     * @return null|array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUsers($token)
    {
        try{
            $response = $this->client->request('GET', 'https://api-emea.eventscloud.com/api/ds/v1/attendeelist/'.env('ACCOUNT_ID').'/'.env('EVENT_ID').'?accesstoken='.$token);
            $users = json_decode($response->getBody())->ResultSet;
        }catch (\Exception $e) {
            echo $e->getMessage();
            $users = null;
        }

        return $users;
    }

    /**
     * Get Session List
     * @param $token
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSessionList($token)
    {
        try{
            $response = $this->client->request('GET', 'https://api-emea.eventscloud.com/api/ds/v1/sessionlist/'.env('ACCOUNT_ID').'/'.env('EVENT_ID').'?accesstoken='.$token.'&language=rus');
            $sessionList = json_decode($response->getBody())->ResultSet;
        }catch (\Exception $e) {
            $sessionList = null;
        }

        return $sessionList;
    }

    /**
     * Get Session Personal List
     * @param $token, $page, $data
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSessionPersonalList($token, $page = 1, $data = [])
    {
        try{
            do {
                $response = $this->client->request('GET', 'https://api-emea.eventscloud.com/api/ds/v1/regsessionlist/' . env('ACCOUNT_ID') . '/' . env('EVENT_ID') . '?accesstoken=' . $token.'&pageNumber='.$page.'&pageSize=500');
                $sessionList = json_decode($response->getBody())->ResultSet;
                $data = array_merge($data, $sessionList);
                $page++;
                var_dump(count($data));
            } while(!empty($sessionList));
        }catch (\Exception $e) {
            echo $e->getMessage();
            $this->getSessionPersonalList($token, $page, $data);
        }

        return $data;
    }

    /**
     * Get Session Personal List
     * @param $token, $id
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSessionPersonalListById($token, $id)
    {
        try{
            $response = $this->client->request('GET', 'https://api-emea.eventscloud.com/api/ds/v1/regsessionlist/' . env('ACCOUNT_ID') . '/' . env('EVENT_ID') . '?accesstoken=' . $token.'&attendeeid='.$id);
            $sessionList = json_decode($response->getBody())->ResultSet;
        }catch (\Exception $e) {
            echo $e->getMessage();
            $sessionList = null;
        }

        return $sessionList;
    }

    /**
     * Get Session List
     * @param $token
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSpeakers($token)
    {
        try{
            $response = $this->client->request('GET', 'https://api-emea.eventscloud.com/api/ds/v1/speakerlist/'.env('ACCOUNT_ID').'/'.env('EVENT_ID').'?accesstoken='.$token.'&language=rus');
            $speakers = json_decode($response->getBody())->ResultSet;
        }catch (\Exception $e) {
            $speakers = null;
        }

        return $speakers;
    }

    /**
     * Get User data
     * @param $token
     * @param $id
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUserData($token, $id)
    {
        try{
            $response = $this->client->request('GET', 'https://api-emea.eventscloud.com/api/ds/v1/attendeelist/'.env('ACCOUNT_ID').'/'.env('EVENT_ID').'?accesstoken='.$token.'&language=rus&attendeeid='.$id);
            $result = json_decode($response->getBody(), true)['ResultSet'][0];
        }catch (\Exception $e) {
            $result = null;
        }

        return $result;
    }
}
