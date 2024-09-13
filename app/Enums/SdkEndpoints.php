<?php

namespace App\Enums;

enum SdkEndpoints: string
{
    case PORNHUB_FEED_PORNSTARS = 'https://www.pornhub.com/files/json_feed_pornstars.json';


    public function retrievePornHubUrls(): array
    {
        return
            [

                self::PORNHUB_FEED_PORNSTARS->value
            ];
    }

}
