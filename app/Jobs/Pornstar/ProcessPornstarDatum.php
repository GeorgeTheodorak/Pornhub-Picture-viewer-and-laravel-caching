<?php

namespace App\Jobs\Pornstar;

ini_set('memory_limit', '2G');
ini_set('max_execution_time', '7200'); // 7200 seconds = 2 hours

use App\Managers\PornstarManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessPornstarDatum implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $datum;

    /**
     * Create a new job instance.
     *
     * @param array $datum
     * @return void
     */
    public function __construct(array $datum)
    {
        $this->datum = $datum;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle():void
    {
        try {
            $pornstarManager = new PornstarManager();
            Log::info("Processing data for {$this->datum['name']}");
            $pornstarManager->entryPoint($this->datum);
            $pornstarManager->commitChanges();
        } catch (Throwable $e) {
            Log::error("Failed to process {$this->datum['name']}: " . $e->getMessage());
        }
    }
}
