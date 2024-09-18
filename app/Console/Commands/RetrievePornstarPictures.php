<?php

namespace App\Console\Commands;

use App\Managers\PornstarPictureManager;
use App\Models\Pornstar;
use App\Services\PornstarPictureService;
use Illuminate\Console\Command;

class RetrievePornstarPictures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:retrieve-pictures';

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
        $pornstarPictureService->buildPornstarPictureUrls();

        $pornstarData = $pornstarPictureService->getPornstarIdsToThumbnailsUrl();
        $pornstarPictureService->sendRequestsAndSave();
        $pornstarPictureService->commitChanges();
    }
}
