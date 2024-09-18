<?php

namespace App\Jobs\Daily;

use App\Jobs\Pornstar\RetrievePornstarPhotos;
use App\Services\RetrievePornhubData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DailyRetrievePornstarsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
//        $updatePornhubEntriesStatus = false;
//        Log::getLogger()->info("Starting update job.");
//        try {
//            // Static call to handle retrieve data from pornhub.
//            $updatePornhubEntriesStatus = RetrievePornhubData::runUpdate();
//        } catch (\Throwable $e) {
//            Log::getLogger()->critical("Job failed with error.");
//            Log::error($e);
//        }
//
//        if (!$updatePornhubEntriesStatus) {
//            Log::getLogger()->error("Job status is not true. Terminating job and will not update photos.");
//            return;
//        }

        // spawn new job handle picture downloads and updates.
        RetrievePornstarPhotos::dispatch();
    }

    private function info(string $string)
    {
    }
}
