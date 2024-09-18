<?php

namespace App\Enums;

enum SdkEndpoints: string
{
    CONST PORNHUB_FEED_PORNSTARS_REQUEST_IDENTIFIER = 1;
    case PORNHUB_FEED_PORNSTARS = 'https://www.pornhub.com/files/json_feed_pornstars.json';


    public function retrievePornHubUrls(): array
    {
        return
            [
                self::PORNHUB_FEED_PORNSTARS->value
            ];
    }

    public function getRequestType(){

        switch ($this) {
            case self::PORNHUB_FEED_PORNSTARS:
                return self::PORNHUB_FEED_PORNSTARS_REQUEST_IDENTIFIER;
        }
    }
}
