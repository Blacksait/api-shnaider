<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use App\Services\ScoreService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $scoreService, $clientService;

    public function __construct(ScoreService $scoreService, ClientService $clientService)
    {
        $this->middleware('auth:api', ['except' => ['login','register', 'set']]);
        $this->scoreService = $scoreService;
        $this->clientService = $clientService;
    }

    /**
     * @deprecated
     * Store a new user. (make for test)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|integer',
        ]);

        try
        {
            $user = new User;
            $user->email = $request->input('email');
            $user->password = app('hash')->make($request->input('password'));
            $user->save();

            return response()->json($user->fresh(), 201);

        }
        catch (\Exception $e)
        {
            return response()->json( [
                'entity' => 'users',
                'action' => 'create',
                'result' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a JWT via given credentials.  (Maybe rework to Repository pattern)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|integer',
            'login' => 'required|email',
            'confirmShowName' => 'boolean'
        ]);

        $credentials = ['email' => $request->login, 'password' => $request->password];

        if (! $token = auth()->setTTL(28800)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user()->makeVisible('password')->toArray();
        $user['nick'] = 'User ' . substr(preg_replace('%[^A-Za-z0-9]%', null, $user['password']), -5);
        unset($user['password']);

        $this->scoreService->storeActivity([
            'title' => 'auth',
            'attendee_id' => $user['attendee_id']
        ]);

        $model = User::find($user['attendee_id']);
        $model->confirmShowName = (int) $request->confirmShowName;
        $model->save();

        $auth = $this->clientService->getAuthHCC($user);

        return $this->respondWithTokenAndData($token, $user, $auth);
    }

    /**
     * Get user details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user()->makeVisible('password');
        $nick = 'User ' . substr(preg_replace('%[^A-Za-z0-9]%', null, $user->password), -5);
        $user = $user->makeHidden('password')->toArray();
        $auth = $this->clientService->getAuthHCC($user);

        return response()->json(array_merge($user, compact('auth', 'nick')));
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->setTTL(28800)->refresh());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * WebHook reg/update user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function set(Request $request)
    {
        DB::table('webhook')->insert([
            'text' => json_encode($request->all())
        ]);

        try{
            $data = $request->only(['attendeeid']);
            $result['data'] = $this->clientService->setUserData($data['attendeeid']);
            $result['status'] = 200;
        } catch (\Exception $e){
            $result['data'] = ['error' => $e->getMessage()];
            $result['status'] = 200;
        }

        return response()->json($result['data'], $result['status']);
    }
}