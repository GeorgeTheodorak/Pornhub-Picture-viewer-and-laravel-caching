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
        $updatePornhubEntriesStatus = false;
        //@@todo modify the $app in cofing to auto use __method__ and __class_ for logging do that in the future no time.
        Log::getLogger()->info("~DailyRetrievePornstarsJob~Starting update/Fetch job from RetrievePornhubData::runUpdate() .");
        try {
            // Static call to handle retrieve data from pornhub.
            $updatePornhubEntriesStatus = RetrievePornhubData::runUpdate();
        } catch (\Throwable $e) {
            Log::getLogger()->critical("~DailyRetrievePornstarsJob~ Job failed with error.");
            Log::error($e);
        }
        if (!$updatePornhubEntriesStatus) {
            Log::getLogger()->error("~DailyRetrievePornstarsJob~ Job status is not true. Terminating job and will not update photos.");
            return;
        }

        // spawn new job handle picture downloads and updates.
        RetrievePornstarPhotos::dispatch();
        return;
    }

    private function info(string $string)
    {
    }
}
