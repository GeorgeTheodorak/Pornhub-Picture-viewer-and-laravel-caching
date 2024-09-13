<?php

namespace App\Sdks;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

abstract class BaseSdk
{
    protected Client $_client;
    protected string $_baseUri;

    public function __construct(
        protected readonly string $baseUri
    )
    {
        $this->_client = new Client(['base_uri' => $baseUri]);
    }

    /**
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $uri URI of the request
     * @param array $options Options for the request (headers, body, etc.)
     * @return Request
     */
    protected function prepare(string $method, string $uri, array $options = []): Request
    {
        return new Request($method, $this->_baseUri . $uri, $options['headers'] ?? [], $options['body'] ?? null);
    }

    protected function sendHttpRequest(Request $request): \Psr\Http\Message\ResponseInterface
    {
      return $this->_client->send($request);
    }


    /**
     * Retrieve and process contents from the response.
     *
     * @param Response $response
     * @return array
     */
    abstract protected function retrieveContents(Response $response): array;

    /**
     * Send an HTTP request and return the response.
     *
     * @param Request $request
     * @return Response
     */
    abstract protected function sendRequest(Request $request): Response;

    /**
     * Example method to execute a request and process the response.
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $uri URI of the request
     * @param array $options Options for the request (headers, body, etc.)
     * @return array
     */
    public function execute(string $method, string $uri, array $options = []): array
    {
        $request = $this->prepare($method, $uri, $options);
        $response = $this->sendRequest($request);
        return $this->retrieveContents($response);
    }
}
