<?php

namespace App\Jobs;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Queue;

class RetrievePornstarPhotos extends Command
{

    protected $signature = 'retrieve:photos';
    protected $description = 'Retrieve pornstar photos from JSON feed';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Step 1: Fetch JSON data from the URL
        $url = \SdkEndpoints::StaticUrlJsonFeedPornstars->value;
        $response = Http::get($url);

        if ($response->failed()) {
            $this->error('Failed to retrieve JSON data.');
            return;
        }

        $data = $response->json();
        $chunks = array_chunk($data['pornstars'], 10); // Split data into chunks of 10

        foreach ($chunks as $chunk) {
            Queue::push(new DownloadPhotosJob($chunk));
        }

        $this->info('Photos are being downloaded...');
    }
}
