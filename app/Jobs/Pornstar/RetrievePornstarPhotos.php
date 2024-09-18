<?php

namespace App\Jobs\Pornstar;

ini_set('memory_limit', '2G');
ini_set('max_execution_time', '7200'); // 7200 seconds = 2 hours

use App\Managers\PornstarManager;
use App\Services\PornstarPictureService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RetrievePornstarPhotos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Beginning to retrieve photos for pornstars");
        $pornstarPictureService = new PornstarPictureService();

        // run build first.
        $pornstarPictureService->buildPornstarPictureUrls();

        // send requests and save to db.
        $pornstarPictureService->sendRequestsAndLoad();

        // Commit changes.
        $pornstarPictureService->commitChanges();

        Log::info("Job finished.");
    }
}
