<?php

namespace App\Console\Commands;

use App\Managers\PornstarManager;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

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
    public function handle()
    {

    }
}
