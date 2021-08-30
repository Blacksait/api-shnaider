<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateStat extends Command
{
    protected $name = 'generate:stat';
    protected $description = "Get stat";
    protected $date = '2021-04-27';

    public function handle()
    {
        try {
            $statistics = DB::table('stat')->whereBetween('dtime', ['2021-04-27 09:00:00', '2021-04-27 18:30:00'])->get();
            $result = [];
            foreach ($statistics as $stat) {
                $users = explode(';', $stat->ticketsonline);
                foreach ($users as $user_id) {
                    if(!isset($result[$stat->id_room][$user_id]))
                        $result[$stat->id_room][$user_id]['min'] = 1;
                    else
                        $result[$stat->id_room][$user_id]['min']++;
                    try {
                        $user = User::find($user_id);
                        $result[$stat->id_room][$user_id]['fname'] = $user->fname?:'';
                        $result[$stat->id_room][$user_id]['lname'] = $user->lname?:'';
                        $result[$stat->id_room][$user_id]['email'] = $user->email?:'';
                        $result[$stat->id_room][$user_id]['mphone'] = $user->mphone?:'';
                        $result[$stat->id_room][$user_id]['city'] = $user->city?:'';
                        $result[$stat->id_room][$user_id]['company'] = $user->company?:'';
                        $result[$stat->id_room][$user_id]['attendee_id'] = $user->attendee_id?:'';
                    } catch (\Exception $e){
                        echo $e->getMessage().PHP_EOL;
                        unset($result[$stat->id_room][$user_id]);
                    }
                }
            }
            //unique users
//            $counts = [];
//            foreach ($result as $key => $value){
//                $counts[$key] = count($result[$key]);
//            }
            //count unique users
//            $counts = [];
//            foreach ($result as $key => $value){
//                foreach ($value as $user){
//                    $counts[$user['attendee_id']] = 1;
//                }
//            }
//            dd($counts, count($counts));
            //add to google sheet
//            foreach ($result as $location_id => $value) {
//                foreach ($value as $user_id => $user) {
//                    $this->googleSheet($user_id, $user, $location_id);
//                }
//            }
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    private function googleSheet($user_id, $user, $location_id)
    {
        try {
            $client = new \Google_Client();
            $client->setApplicationName("shnaider");
            $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
            $client->setAuthConfig(base_path() . '/public/MyProject-098ec42a6a12.json');
            $client->setAccessToken("098ec42a6a1281836f35611ccdd30f85948dafe6");

            $service = new \Google_Service_Sheets($client);

            $options = array('valueInputOption' => 'RAW');
            $values = [[$user_id, $user['min'], $user['fname'], $user['lname'], $user['email'], $user['mphone'], $user['city'], $user['company']]];
            $body = new \Google_Service_Sheets_ValueRange(['values' => $values]);


            if((int) $location_id === 99)
                $location_name = 'Секретная комната';
            elseif((int) $location_id === 50)
                $location_name = 'Любая страница без плееера трансляции';
            elseif((int) $location_id === 1)
                $location_name = DB::table('locations')->first()->name;
            else
                $location_name = DB::table('locations')->skip((int) $location_id - 1)->first()->name;

            $result = $service->spreadsheets_values->append("1ynICWpNKHVEV71YXmdvBYqreROdiCG_ribbxhLyosj8", $location_name.' '.$this->date.'!A1:H1', $body, $options);
            if($result)
                echo $user_id.' '.$user['email'].PHP_EOL;
            else
                echo 'Неудачно!';
        } catch (\Exception $e){
//            echo $e->getMessage().PHP_EOL;
//            var_dump($user_id, $user, $location_id);
//            exit;
            $this->googleSheet($user_id, $user, $location_id);
        }
    }
}
