<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Carbon\Carbon;
use http\Exception\RuntimeException;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PornstarPictureController extends Controller
{

    public function getPicture(int $pornstarId, string $type)
    {
        $paddedString = str_pad($pornstarId, 9, '0', STR_PAD_LEFT);
        $picturePath = 'pornstars/thumbnails/' . $paddedString . '/' . $type . '/';

        $cacheKey = "pornstar_{$pornstarId}_{$type}_picture";
        $cachedImage = Cache::get($cacheKey);

        if ($cachedImage) {
            // Return the cached image with appropriate headers
            return response()->file($cachedImage['path'], $cachedImage['headers']);
        }

        // Get all files in the directory
        $filesInDir = Storage::disk('media')->files($picturePath);
        $imageExtensions = ['jpg', 'jpeg', 'png'];

        foreach ($filesInDir as $file) {

            if (in_array(pathinfo($file, PATHINFO_EXTENSION), $imageExtensions)) {

                $filePath = Storage::disk('media')->path($file);
                $lastModified = Carbon::createFromTimestamp(Storage::disk('media')->lastModified($file));
                $etag = md5_file($filePath);
                $headers = [
                    'Last-Modified' => $lastModified->toRfc7231String(),
                    'ETag' => $etag,
                    'Cache-Control' => 'public, max-age=3600', // Cache for 1 hour
                ];
                Cache::put($cacheKey, ['path' => $filePath, 'headers' => $headers], 3600);

                return response()->file($filePath, $headers);
            }
        }

        // If no file is found, return 404 or a default image
        return response()->json(['error' => 'Image not found'], 404);
    }
}
