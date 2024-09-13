<?php

namespace App\Sdks;

use App\Enums\SdkEndpoints;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class PornhubSdk extends BaseSdk
{
    public function __construct(

    ){
        parent::__construct(SdkEndpoints::PORNHUB_FEED_PORNSTARS->value);
    }

    /**
     * Used to retrieve the contents in array format
     * @param Response $response
     * @return array
     */
    protected function retrieveContents(Response $response): array
    {
        $baseResponse = $this->retrieveContents();

        // TODO: Implement retrieveContents() method.
    }

    protected function sendRequest(Request $request): Response
    {
    }

    public function prepareAndSendRequest(){

        $requestBody = $this->prepare(
            'GET',

        );
        $response = $this->se();

    }


}
