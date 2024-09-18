<?php

namespace App\Services;
ini_set('memory_limit', '2G');
ini_set('max_execution_time', '7200'); // 7200 seconds = 2 hours
use App\Enums\SdkEndpoints;
use App\Jobs\Pornstar\ProcessPornstarDatum;
use App\Managers\PornstarManager;
use App\Models\RequestLogs;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Log;

class RetrievePornhubData
{
    const RETRY_ATTEMPTS = 3;
    const TIMEOUT_SECONDS = 30;

    public static function runUpdate():bool
    {
        $requestType = SdkEndpoints::PORNHUB_FEED_PORNSTARS;
        $latestPornhubRequestStatus = RequestLogs::query()->where(
            'request_type', $requestType->getRequestType()
        )->orderBy('created_at', 'desc')->first();

        $latestPornhubRequestGeneratedDatetime = null;
        if (
            $latestPornhubRequestStatus
            && isset($latestPornhubRequestStatus['response_body'])
        ) {
            $responseEncoded = json_decode($latestPornhubRequestStatus['response_body'], true);
            $latestPornhubRequestGeneratedDatetime = $responseEncoded['generationDate'] ?? null;
        }

        Log::info("Starting automatic update.");

        // Initialize HTTP client with timeouts and retries
        $client = new Client([
            'verify' => false,
            'timeout' => self::TIMEOUT_SECONDS, // Set a timeout
        ]);

        try {
            // Retrieve the response with retries
            $response = self::makeRequestWithRetries($client, SdkEndpoints::PORNHUB_FEED_PORNSTARS->value);

            if ($response) {
                $bodyContents = json_decode($response->getBody()->getContents(), true);

                // Check if the response body is valid
                if (is_array($bodyContents)) {
                    $timestampForContent = $bodyContents['generationDate'] ?? null;

                    // Proceed with the update if there's new data
                    if ($latestPornhubRequestGeneratedDatetime !== $timestampForContent) {
                        Log::error("The timestamp is not the same, beginning update.");
                        self::updateData($bodyContents);
                        return true;
                    } else {
                        Log::info("No new data found.Skipping job cause it's up to date.");
                    }

                    self::saveRequestLog(200, $bodyContents);
                } else {
                    Log::error("Invalid response body format.");
                    self::saveRequestLog(500, ['error' => 'Invalid response format']);
                }
            }
        } catch (RequestException $e) {
            // Handle request-specific exceptions (ClientException, ServerException, etc.)
            Log::error("RequestException: " . $e->getMessage());
            self::saveRequestLog($e->getCode(), ['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            // Handle general exceptions
            Log::error("Exception: " . $e->getMessage());
            self::saveRequestLog(500, ['error' => $e->getMessage()]);
        }
        return false;
    }

    private static function updateData(array $content): void
    {
        $chunks = array_chunk($content['items'], 100);
        Log::error("Initializing jobs to handle pornstar updates / creations.");
        foreach ($chunks as $batch) {
            foreach ($batch as $datum) {
                // Dispatch a job to process each datum
                ProcessPornstarDatum::dispatch($datum);
            }
        }
    }

    private static function updateData2(array $content): void
    {
        foreach ($content['items'] as $item) {
            try {
                $pornstarManager = new PornstarManager();
                Log::info("Processing data for {$item['name']}");
                $pornstarManager->entryPoint($item);
                $pornstarManager->commitChanges();
            } catch (\Exception $e) {
                Log::error("Failed to process {$item['name']}: " . $e->getMessage());
            }
        }
    }


    private static function saveRequestLog(
        int    $responseStatus,
        ?array $saveContent = null,
    ): void
    {

        if (!is_null($saveContent)) {
            $saveContent = ['generationDate' => $saveContent['generationDate']];
        }

        try {
            $newRequestLog = new RequestLogs();
            $newRequestLog['request_type'] = SdkEndpoints::PORNHUB_FEED_PORNSTARS->getRequestType();
            $newRequestLog['method'] = 'GET';
            $newRequestLog['url'] = SdkEndpoints::PORNHUB_FEED_PORNSTARS->value;
            $newRequestLog['response_status'] = $responseStatus;
            $newRequestLog['response_body'] = json_encode($saveContent);

            $newRequestLog->save();
        } catch (\Exception $e) {
            Log::error("Failed to save request log: " . $e->getMessage());
        }
    }

    private static function makeRequestWithRetries(Client $client, string $url, int $attempts = self::RETRY_ATTEMPTS)
    {
        $attempt = 0;
        do {
            try {
                return $client->get($url);
            } catch (ConnectException|ClientException|ServerException $e) {
                $attempt++;
                Log::warning("Attempt $attempt failed: " . $e->getMessage());
                if ($attempt >= $attempts) {
                    throw $e;
                }
            }
        } while ($attempt < $attempts);

        return null;
    }
}
