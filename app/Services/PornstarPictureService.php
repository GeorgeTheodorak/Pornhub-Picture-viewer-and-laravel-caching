<?php

namespace App\Services;

use App\Enums\PictureType;
use App\Managers\PornstarPictureManager;
use App\Models\Pornstar;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Facades\Log;

class PornstarPictureService
{
    private Client $_client;

    /**
     * Populate this to handle with save.
     * @var array
     */
    private array $_pornstarIdsToThumbnailsUrl = [];
    private array $_imageRetrieveData = [];
    private PornstarPictureManager $_pornstarPictureManager;
    private int $maxRetries = 3;
    private int $batchSize = 5;
    private int $totalRequests = 0;
    private int $completedRequests = 0;
    private int $paginationSize = 151;// left 151 after finding sweetspot.
    private int $_totalPornstarsWithPictures = 0;

    public function __construct(private readonly ?int $_pornstarId = null)
    {
        $this->_client = new Client(['timeout' => 10, 'verify' => false]);
        $this->_pornstarPictureManager = new PornstarPictureManager();
    }

    public function buildAndProcessInBatches(): void
    {
        $query = $this->_pornstarId
            ? Pornstar::where('id', $this->_pornstarId)->whereNotNull('thumbnails') // Single Pornstar query with thumbnails not null
            : Pornstar::query()
                ->whereNotNull('thumbnails');


        $queryMax = clone $query;

        // Keep to calculate the total number of pornstars for logging purposes.
        $this->_totalPornstarsWithPictures = $queryMax->count();


        // Paginate the query in batches of $this->paginationSize
        // We need to process each chunk to reduce ram usage
        $query->chunk($this->paginationSize, function ($pornstars) {
            $this->processBatch($pornstars);
        });

    }

    /**
     * Function used by chunk lambda only.
     * @param $pornstars
     * @return void
     */
    private function processBatch($pornstars): void
    {
        $this->totalRequests = 0;
        $this->completedRequests = 0;
        $this->_pornstarIdsToThumbnailsUrl = [];
        $this->_imageRetrieveData = [];

        $this->buildPornstarPictureUrls($pornstars);
        $this->sendRequestsAndPopulate();
        $this->commitChanges();
    }

    /**
     * @param $pornstars
     * @return void
     */
    public function buildPornstarPictureUrls($pornstars): void
    {
        foreach ($pornstars as $pornstar) {
            $thumbnails = $pornstar['thumbnails'] ?? [];

            // If no thumbnails skip
            // Fixed when not null was applied on query
            if (empty($thumbnails)) {
                continue;
            }

            $pcPicture = $this->filterThumbnail($thumbnails, 'pc');
            $mobilePicture = $this->filterThumbnail($thumbnails, 'mobile', $pcPicture);
            $tabletPicture = $this->filterThumbnail($thumbnails, 'tablet', $pcPicture);

            // Populate.
            $this->_pornstarIdsToThumbnailsUrl[$pornstar['pornhub_id']] = [
                PictureType::PC->value => $pcPicture,
                PictureType::MOBILE->value => $mobilePicture,
                PictureType::TABLET->value => $tabletPicture,
            ];
        }
    }

    /**
     * Use function to filter thumbnails to retrieve correct urls.
     * @param array $thumbnails
     * @param string $type
     * @param array|null $comparisonPicture
     * @return array|null
     */
    private function filterThumbnail(array $thumbnails, string $type, ?array $comparisonPicture = null): ?array
    {
        $picture = collect($thumbnails)->firstWhere('type', $type);
        return isset($picture['urls'], $comparisonPicture['urls']) && $picture['urls'] === $comparisonPicture['urls']
            ? null
            : $picture;
    }

    /**
     * Use to queue requests , if the device picture urls is null will be skipped.
     * @return void
     */
    public function sendRequestsAndPopulate(): void
    {
        $promises = [];

        foreach ($this->_pornstarIdsToThumbnailsUrl as $pornhubId => $urls) {
            foreach ($urls as $deviceType => $devicePicture) {
                if (!$devicePicture) continue;

                foreach ($devicePicture['urls'] as $index => $url) {
                    $this->totalRequests++; // Increment total requests
                    $this->queueRequest($promises, $pornhubId, $deviceType, $devicePicture['height'], $devicePicture['width'], $url, $index);
                }
            }
        }

        // Batch requests and wait for results
        foreach (array_chunk($promises, $this->batchSize, true) as $batch) {
            Utils::settle($batch)->wait();
        }
    }

    private function queueRequest(array &$promises, int $pornhubId, string $deviceType, int $height, int $width, string $url, int $index, int $attempt = 1): void
    {
        $promises["$pornhubId-$deviceType-$index"] = $this->_client->getAsync($url)
            ->then(
                function ($response) use ($pornhubId, $deviceType, $height, $width, $index) {
                    $this->setImageRetrieveData($pornhubId, $deviceType, $index, $response->getBody()->getContents(), $height, $width);
                    $this->logProgress($deviceType); // Log progress after successful response
                },
                function ($exception) use ($pornhubId, $deviceType, $height, $width, $url, $index, $attempt) {
                    $this->handleRequestFailure($promises, $pornhubId, $deviceType, $height, $width, $url, $index, $attempt, $exception);
                }
            );
    }

    /**
     * Request failure must be run when a request fials the execution , delay is applied here based on the max retries.
     * @param array $promises
     * @param int $pornhubId
     * @param string $deviceType
     * @param int $height
     * @param int $width
     * @param string $url
     * @param int $index
     * @param int $attempt
     * @param $exception
     * @return void
     */
    private function handleRequestFailure(array &$promises, int $pornhubId, string $deviceType, int $height, int $width, string $url, int $index, int $attempt, $exception): void
    {
        if ($attempt < $this->maxRetries) {
            $nextAttempt = $attempt + 1;
            $delay = pow(2, $attempt);
            Log::error("Retrying... ($nextAttempt/{$this->maxRetries}) for Pornstar ID: $pornhubId, Device: $deviceType, Index: $index after $delay seconds. Exception: " . $exception->getMessage());
            sleep($delay);
            $this->queueRequest($promises, $pornhubId, $deviceType, $height, $width, $url, $index, $nextAttempt);
        } else {
            Log::error("Failed after {$this->maxRetries} attempts for Pornstar ID: $pornhubId, Device: $deviceType, Index: $index. Exception: " . $exception->getMessage());
        }
    }

    /**
     * Increment completed and set image retrieve data , used to populate array so we can update/create them later using appropriate manager
     * @param int $pornhubId
     * @param string $deviceType
     * @param int $index
     * @param string $imageData
     * @param int $height
     * @param int $width
     * @return void
     */
    private function setImageRetrieveData(int $pornhubId, string $deviceType, int $index, string $imageData, int $height, int $width): void
    {
        $this->_imageRetrieveData[$pornhubId][$index] = [
            'base64' => $imageData,
            'height' => $height,
            'width' => $width,
            'deviceType' => $deviceType,
        ];
        $this->completedRequests++; // Increment completed requests
    }

    /**
     * Logs the progress of requests with percentage completion.
     */
    private function logProgress($type): void
    {
        $percentage = round(($this->completedRequests / $this->totalRequests) * 100, 2);
        Log::info("Progress: {$this->completedRequests}/{$this->totalRequests} requests completed ({$percentage}%) type: " . $type . " total pornstar requests are: " . $this->_totalPornstarsWithPictures);
    }

    /**
     * Use PornstarPictureManager to save pictures.
     * @return void
     */
    public function commitChanges(): void
    {
        foreach ($this->_imageRetrieveData as $pornStarId => $picturesData) {
            foreach ($picturesData as $picture) {
                try {
                    $this->_pornstarPictureManager->savePicture($pornStarId, $picture);
                } catch (\Throwable $e) {
                    Log::error("Error saving picture for Pornstar ID: $pornStarId - " . $e->getMessage());
                }
            }
        }
    }

    public function getPornstarIdsToThumbnailsUrl(): array
    {
        return $this->_pornstarIdsToThumbnailsUrl;
    }

    public function getImageRetrieveData(): array
    {
        return $this->_imageRetrieveData;
    }
}
