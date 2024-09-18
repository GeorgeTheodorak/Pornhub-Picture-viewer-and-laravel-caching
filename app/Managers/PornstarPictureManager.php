<?php

namespace App\Managers;

use App\Enums\PictureType;
use App\Utils\PictureUtil;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SebastianBergmann\Diff\Utils\FileUtils;

class PornstarPictureManager
{
    /**
     * Pad the pornstar ID to a fixed length with leading zeros.
     *
     * @param int $pornstarId
     * @return string
     */
    private function padPornstarId(int $pornstarId): string
    {
        return str_pad($pornstarId, 9, '0', STR_PAD_LEFT);
    }

    /**
     * Save pictures based on the provided thumbnails data
     *
     * @param int $pornstarId
     * @param array $picture
     * @return void
     */
    public function savePicture(int $pornstarId, array $picture): void
    {

        $pictureType = PictureType::from($picture['deviceType']);
        $paddedPornstarId = $this->padPornstarId($pornstarId);

        $width = $picture['width'];
        $height = $picture['height'];

        // Generate a unique identifier for the image based on width and height
        $identifier = 'w_' . $width . '_h_' . $height;
        // Generate a unique filename using the identifier
        $filename = $this->generateFilename($paddedPornstarId, $identifier, $pictureType);
        $filePath = 'pornstars/thumbnails/' . $filename;

        // Empty path and save.
        $folderPathToEmpty = 'pornstars/thumbnails/' . $paddedPornstarId . '/' . $pictureType->value . '/';
        PictureUtil::deleteAllFilesInFolder($folderPathToEmpty);


        Storage::disk('media')->put($filePath, $picture['base64']);
    }

    /**
     * Generate a unique filename for the thumbnail
     *
     * @param string $paddedPornstarId
     * @param string $identifier
     * @param PictureType $pictureType
     * @return string
     */
    private function generateFilename(string $paddedPornstarId, string $identifier, PictureType $pictureType): string
    {
        // Create a unique filename using paddedPornstarId, identifier, and timestamp
        return $paddedPornstarId . '/' . $pictureType->value . '/' . $identifier . '.jpg';
    }
}
