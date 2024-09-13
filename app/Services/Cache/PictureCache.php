<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PictureCache
{
    protected int $cacheDuration; // Cache duration in minutes

    public function __construct()
    {
        // Set cache duration; you can change this value as needed
        $this->cacheDuration = 60; // Cache for 60 minutes
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

        // If the picture does not exist in the cache or web drive, return null
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
