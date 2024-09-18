<?php

namespace App\Utils;

use App\Enums\PictureType;
use App\Managers\PornstarPictureManager;
use App\Models\Pornstar;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Facades\Storage;
use function GuzzleHttp\Promise\Promise\settle;

class PictureUtil
{
  public static function retrievePicturePathForPornstar(int $pornstarId): string
  {

      return Storage::disk('media')->url('pornstars/thumbnails/');
  }
}
