<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

ini_set('memory_limit', '2G');
ini_set('max_execution_time', '7200'); // 7200 seconds = 2 hours


class RetrievePornstarPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:retrieve-pornstar-photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client([
            'verify' => false,
        ]);

        $response = $client->get('https://www.pornhub.com/files/json_feed_pornstars.json');

// Handle the response as needed
        $data = $response->getBody()->getContents();

        $data = json_decode($data);
                // Step 2: Split the data into chunks
        //        $chunks = array_chunk($data['pornstars'], 10); // Split data into chunks of 10

        //        // Step 3: Process each chunk in a thread (Queue)
        //        foreach ($chunks as $chunk) {
        //            Queue::push(new DownloadPhotosJob($chunk));
        //        }
        //
        //        $this->info('Photos are being downloaded...');

        foreach($data as $datum){

            print_r($datum);
        }

    }
}
