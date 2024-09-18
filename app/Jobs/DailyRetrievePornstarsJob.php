<?php

namespace App\Jobs;
namespace App\Jobs;

use App\Managers\PornstarManager;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DailyRetrievePornstarsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        // Constructor code if needed
    }

    public function handle()
    {

        $client = new Client([
            'verify' => false,
        ]);

        $response = $client->get('https://www.pornhub.com/files/json_feed_pornstars.json');

        // Handle the response as needed
        $data = $response->getBody()->getContents();

        $data = json_decode($data,true);

        foreach($data['items'] as $datum){

            $pornstarManager = new PornstarManager();
            $this->info('Processing ' . $datum['name']);
            $pornstarManager->entryPoint($datum);
            $pornstarManager->commitChanges();
        }
    }

    private function info(string $string)
    {

    }
}
