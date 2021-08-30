<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get('/score', 'ScoreController@index');

$router->get('/rooms', 'PollController@index');

$router->get('/meeting', 'MeetingController@index');

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('schedule-common', 'SessionController@all');

    $router->get('speakers', 'SpeakerController@all');

    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');

    $router->group(['prefix' => 'meeting'], function () use ($router) {
        $router->group(['middleware' => 'auth'], function ($router) {
            $router->get('list', 'MeetingController@getMeeting');
        });
        $router->post('update', 'MeetingController@update');
        $router->post('delete', 'MeetingController@destroy');
    });

    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('set', 'AuthController@set');
    });

    $router->group(['middleware' => 'auth'], function ($router) {
        $router->get('me', 'AuthController@me');
        $router->get('poll', 'PollController@all');
        $router->get('schedule-user', 'SessionController@allPersonal');
    });

    $router->group(['prefix' => 'stream'], function ($router) {
        $router->group(['middleware' => 'auth'], function ($router) {
            $router->get('/{stream_id}', 'StreamController@getStreamSettings');
        });
        $router->get('/marks/{stream_id}', 'StreamController@getTimeMarks');
    });

    $router->group(['prefix' => 'poll', 'middleware' => 'auth'], function ($router) {
        $router->get('list', 'PollController@all');
        $router->post('store', 'PollController@storeUserAnswer');
        $router->post('evaluation/store', 'PollController@storeUserEvaluation');
        $router->get('result/{poll_id}', 'PollController@getPollResult');
    });

    $router->group(['prefix' => 'score'], function ($router) {
        $router->post('update', 'ScoreController@update');
        $router->post('store', 'ScoreController@store');
        $router->get('show', 'ScoreController@score');
        $router->get('rating', 'ScoreController@rating');
    });
});
