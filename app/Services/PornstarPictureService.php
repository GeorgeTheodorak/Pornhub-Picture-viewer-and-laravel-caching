<?php

namespace App\Services;

use App\Enums\PictureType;
use App\Managers\PornstarPictureManager;
use App\Models\Pornstar;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\Utils;
use function GuzzleHttp\Promise\Promise\settle;

class PornstarPictureService
{
    private $_pornStarQuery;
    private $_pornstarIdsToThumbnailsUrl = [];
    private Client $_client;
    private array $_imageRetrieveData = [];
    private PornstarPictureManager $_pornstarPictureManager;


    public function __construct(
        private readonly ?int $_pornstarId = null
    )
    {

        if ($this->_pornstarId) {
            $this->_pornStarQuery = Pornstar::find($this->_pornstarId);
        } else {
            $this->_pornStarQuery = Pornstar::all();
        }

        $this->_client = new Client();

        $this->_pornstarPictureManager = new PornstarPictureManager();
    }

    public function buildPornstarPictureUrls(): void
    {

        foreach ($this->_pornStarQuery as $pornstar) {

            $pornstarThumbnails = $pornstar['thumbnails'];

            // If thumbnails empty skip.
            if (empty($pornstarThumbnails)) {
                continue;
            }

            $pcPictureData = [];
            $mobilePictureData = [];
            $tabletPictureData = [];
            // Iterate over thumbnails and check the type
            foreach ($pornstarThumbnails as $thumbnail) {

                if ($thumbnail['type'] === 'pc') {
                    $pcPictureData = $thumbnail;
                } elseif ($thumbnail['type'] === 'mobile') {
                    $mobilePictureData = $thumbnail;
                } elseif ($thumbnail['type'] === 'tablet') {
                    $tabletPictureData = $thumbnail;
                }
            }

            // Check if 'urls' key exists before comparing pc and mobile data
            if (isset($pcPictureData['urls']) && isset($mobilePictureData['urls']) && $pcPictureData['urls'] == $mobilePictureData['urls']) {
                $mobilePictureData = null;
            }

            // Check if 'urls' key exists before comparing pc and tablet data
            if (isset($pcPictureData['urls']) && isset($tabletPictureData['urls']) && $tabletPictureData['urls'] == $pcPictureData['urls']) {
                $tabletPictureData = null;
            }

            $this->_pornstarIdsToThumbnailsUrl[$pornstar['pornhub_id']] = [
                PictureType::PC->value => $pcPictureData,
                PictureType::MOBILE->value => $mobilePictureData,
                PictureType::TABLET->value => $tabletPictureData
            ];
        }

    }


    public function sendRequestsAndSave()
    {
        $this->_pornstarIdsToThumbnailsUrl = array_slice($this->_pornstarIdsToThumbnailsUrl, 0, 100, true);

        // Array to hold promises
        $promises = [];

        // Number of retries
        $maxRetries = 3;

        // Function to send requests with retry logic
        $sendRequest = function ($pornhubId, $deviceType, $height, $width, $url, $index, $attempt = 1) use (&$sendRequest, $maxRetries, &$promises) {

            // Create asynchronous request for each URL
            $promises["$pornhubId-$deviceType-$index"] = $this->_client->getAsync($url, ['verify' => false])
                ->then(
                    function ($response) use ($pornhubId, $deviceType, $index, $height, $width) {
                        // Handle successful response (e.g., save the picture data)
                        $imageData = $response->getBody()->getContents();
                        $this->setImageRetrieveData($pornhubId, $deviceType, $index, $imageData, $height, $width);
                    },
                    function ($exception) use ($pornhubId, $deviceType, $url, $index, $attempt, &$sendRequest, $maxRetries) {
                        // Handle failed request and retry if attempts are left
                        if ($attempt < $maxRetries) {
                            $nextAttempt = $attempt + 1;
                            $delay = pow(2, $attempt);
                            error_log("Retrying... ($nextAttempt/$maxRetries) for Pornstar ID: $pornhubId, Device: $deviceType, Index: $index after $delay seconds");

                            // Wait before retrying
                            sleep($delay);

                            // Retry the request
                            $sendRequest($pornhubId, $deviceType, $url, $index, $nextAttempt);
                        } else {
                            error_log("Failed to download after $maxRetries attempts for Pornstar ID: $pornhubId, Device: $deviceType, Index: $index - " . $exception->getMessage());
                        }
                    }
                );
        };

        // Iterate over each pornstar's URLs and send async requests
        foreach ($this->_pornstarIdsToThumbnailsUrl as $pornhubId => $urls) {

            foreach ($urls as $deviceType => $devicePictureData) {

                if ($devicePictureData === null) {
                    continue;
                }

                $height = $devicePictureData['height'];
                $width = $devicePictureData['width'];

                $urlsForPicture = $devicePictureData['urls'];
                if (is_array($urlsForPicture)) {
                    foreach ($urlsForPicture as $index => $url) {
                        // Call the request sending function with retry logic
                        $sendRequest(
                            $pornhubId,
                            $deviceType,
                            $height,
                            $width,
                            $url,
                            $index,
                        );
                    }
                }
            }
        }

        // Wait for all the promises to settle
        Utils::settle($promises)->wait();
    }


    public function getPornstarIdsToThumbnailsUrl(): array
    {
        return $this->_pornstarIdsToThumbnailsUrl;
    }


    private function setImageRetrieveData(
        int    $pornhubId,
        string $deviceType,
        mixed  $index,
        string $imageData,
        int    $height,
        int    $width
    ): void
    {
        $this->_imageRetrieveData[$pornhubId] = [
            $index => [
                'base64' => $imageData,
                'height' => $height,
                'width' => $width,
                'deviceType' => $deviceType
            ]
        ];
    }

    public function getImageRetrieveData(): array
    {
        return $this->_imageRetrieveData;
    }

    public function commitChanges(): void
    {

        foreach ($this->_imageRetrieveData as $pornStarId => $picturesData) {

            foreach ($picturesData as $picture) {

                try {

                    $this->_pornstarPictureManager->savePicture($pornStarId, $picture);
                } catch (\Throwable $e) {

                    error_log($e->getMessage());
                }
            }
        }
    }
}
