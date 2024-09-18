<?php

namespace App\Console\Commands;

use App\Jobs\Daily\DailyRetrievePornstarsJob;
use App\Services\RetrievePornhubData;
use Illuminate\Console\Command;

ini_set('memory_limit', '2G');
ini_set('max_execution_time', '7200'); // 7200 seconds = 2 hours


class RetrievePornstarData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:retrieve-pornstar-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run to retrieve all the pornstar data and save in sql lite.';

    /**
     * Execute the console command.
     */
    public function handle(){
        DailyRetrievePornstarsJob::dispatch();
    }
}
