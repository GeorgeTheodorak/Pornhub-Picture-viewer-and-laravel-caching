<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

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
        // Step 1: Fetch JSON data from the URL
        $url = 'https://www.pornhub.com/files/json_feed_pornstars.json';
        $response = Http::get($url);

        if ($response->failed()) {
            $this->error('Failed to retrieve JSON data.');
            return;
        }

        $data = $response->json();

        // Step 2: Split the data into chunks
        $chunks = array_chunk($data['pornstars'], 10); // Split data into chunks of 10

        // Step 3: Process each chunk in a thread (Queue)
        foreach ($chunks as $chunk) {
            Queue::push(new DownloadPhotosJob($chunk));
        }

        $this->info('Photos are being downloaded...');
    }
}
