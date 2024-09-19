<?php

namespace App\Console\Commands;

use App\Managers\PornstarPictureManager;
use App\Models\Pornstar;
use App\Services\Cache\PornstarPictureService;
use App\Utils\PictureUtil;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

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

        $pornstarPictureService = new PornstarPictureService();
        $pornstarPictureService->buildAndProcessInBatches();
    }
}
