<?php

namespace App\Utils;

use App\Enums\PictureType;
use App\Managers\PornstarPictureManager;
use App\Models\Pornstar;
use FilesystemIterator;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use function GuzzleHttp\Promise\Promise\settle;

class PictureUtil
{
//    public static function retrievePicturePathForPornstar(int $pornstarId): string
//    {
//        $paddedString = str_pad($pornstarId, 9, '0', STR_PAD_LEFT);
//        return Storage::disk('media')->path('pornstars/thumbnails/' . $paddedString . '/');
//    }


    public static function deleteAllFilesInFolder($folderPath): void
    {
        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }


    public static function retrievePictureCdnForPornstar(int $pornstarId): array
    {
        // Initialize an array to hold the files
        $files = [];

        // Generate the RESTful controller URLs for pc, mobile, and tablet
        $pcUrl = route('pornstar.picture', ['pornstarId' => $pornstarId, 'type' => 'pc']);
        $mobileUrl = route('pornstar.picture', ['pornstarId' => $pornstarId, 'type' => 'mobile']);
        $tabletUrl = route('pornstar.picture', ['pornstarId' => $pornstarId, 'type' => 'tablet']);

        // Check if the image exists using your logic from before
        $getImageFromDirectory = function ($dir) {
            $imageExtensions = ['jpg', 'jpeg', 'png'];
            $filesInDir = Storage::disk('media')->files($dir);

            foreach ($filesInDir as $file) {
                if (in_array(pathinfo($file, PATHINFO_EXTENSION), $imageExtensions)) {
                    return true; // Image exists
                }
            }
            return false;
        };

        // Retrieve the paths from directories
        $paddedString = str_pad($pornstarId, 9, '0', STR_PAD_LEFT);
        $pcDir = 'pornstars/thumbnails/' . $paddedString . '/pc/';
        $mobileDir = 'pornstars/thumbnails/' . $paddedString . '/mobile/';
        $tabletDir = 'pornstars/thumbnails/' . $paddedString . '/tablet/';

        $pcPicture = $getImageFromDirectory($pcDir) ? $pcUrl : null;
        $mobilePicture = $getImageFromDirectory($mobileDir) ? $mobileUrl : ($pcPicture ?: null);
        $tabletPicture = $getImageFromDirectory($tabletDir) ? $tabletUrl : ($pcPicture ?: null);

        $files['pc'] = $pcPicture;
        $files['mobile'] = $mobilePicture;
        $files['tablet'] = $tabletPicture;

        // Return the array of URLs to the controller paths
        return $files;
    }
}
