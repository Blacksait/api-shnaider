<?php

namespace App\Console\Commands;

use App\Models\Session;
use Illuminate\Console\Command;
use App\Services\ClientService;
use Illuminate\Support\Facades\DB;

class GetSessionList extends Command
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
    protected $name = 'get:sessionlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Get session list from API Shnaider and input to DB";

    /**
     * Execute the console command.
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        try {
            DB::table('session_list')->delete();

            $sessionList = $this->clientService->getSessionList();
            if(empty($sessionList))
                echo 'Session list is empty';

            foreach ($sessionList as $session) {
                if($session->locationid === 0)
                    continue;

                $speaker_ids = [];
                foreach (preg_grep("/^speaker_\d+_speakerid$/i", array_keys(get_object_vars($session))) as $el)
                    $speaker_ids[] = $session->$el;
                try {
                    Session::create([
                        'session_id' => $session->sessionid,
                        'speaker_ids' => implode(';', $speaker_ids),
                        'sort' => $session->sort,
                        'name' => mb_convert_encoding($session->name,'UTF-8','HTML-ENTITIES'),//mb_convert_encoding(,'UTF-8','HTML-ENTITIES')
//                    'name' => mb_detect_encoding($session->name, 'HTML-ENTITIES')? mb_convert_encoding($session->name,'UTF-8','HTML-ENTITIES'): $session->name ,
                        'sessiondate' => $session->sessiondate,
                        'starttime' => $session->starttime,
                        'endtime' => $session->endtime,
                        'location_id' => $session->locationid,
                    ]);
                } catch(\Exception $e){
                    echo $e->getMessage();
                }
            }
            echo 'Complete! Session list successfully added to Database.' . PHP_EOL;
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
