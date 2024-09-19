<?php

namespace App\Services\Cache;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

//@@TODO Maybe use in the future.
class PictureCache
{
    protected int $cacheDuration; // Cache duration in minutes

    public function __construct()
    {
        $this->cacheDuration = 60;
    }

    /**
     * @param string $pictureName
     * @return string|null
     */
    public function getPicture(string $pictureName): ?string {

        $cachedPicture = Cache::get($this->getCacheKey($pictureName));

        if ($cachedPicture) {
            return $cachedPicture;
        }

        if (Storage::exists($pictureName)) {
            $pictureContents = Storage::get($pictureName);
            $this->_cachePicture($pictureName, $pictureContents);

            return $pictureContents;
        }
        return null;
    }

    /**
     * Cache the picture in Redis.
     *
     * @param string $pictureName
     * @param string $pictureContents
     * @return void
     */
    protected function _cachePicture(string $pictureName, string $pictureContents): void
    {
        Cache::put($this->getCacheKey($pictureName), $pictureContents, $this->cacheDuration);
    }

    /**
     *
     * @param string $pictureName
     * @return string
     */
    protected function getCacheKey(string $pictureName): string
    {
        return 'picture_cache_' . $pictureName;
    }


}
