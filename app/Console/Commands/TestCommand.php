<?php

namespace App\Console\Commands;

use App\Managers\PornstarPictureManager;
use App\Models\Pornstar;
use App\Services\PornstarPictureService;
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
        print_r(PictureUtil::retrievePictureCdnForPornstar(2));
        // Get distinct ethnicities for the filter dropdown
//        $ethnicities = Pornstar::distinct()->pluck('ethnicity')->filter()->array();

        print_r($ethnicities);
    }
}
