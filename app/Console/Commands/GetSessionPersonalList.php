<?php

namespace App\Console\Commands;

use App\Models\UserSession;
use Illuminate\Console\Command;
use App\Services\ClientService;
use Illuminate\Support\Facades\DB;

class GetSessionPersonalList extends Command
{
    /**
     * @var ClientService
     */
    protected $clientService;

    /**
     * Create a new command instance.
     *
     * @var ClientService
     * @return void
     */
    public function __construct(ClientService $clientService)
    {
        parent::__construct();
        $this->clientService = $clientService;
    }

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'get:usersessionlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Get session personal list from API Shnaider and input into DB";

    /**
     * Execute the console command.
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        try {
            DB::table('user_sessionlist')->delete();

            $sessionList = $this->clientService->getSessionPersonalList();
            if(empty($sessionList))
                exit('Session personal list is empty');

            foreach ($sessionList as $session) {
                try {
                    UserSession::create([
                        'user_id' => $session->attendeeid,
                        'session_id' => $session->sessionid
                    ]);
                } catch(\Exception $e){
                    echo $e->getMessage().PHP_EOL;
                }
            }

            echo 'Complete! Session personal list successfully added to Database.' . PHP_EOL;
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
