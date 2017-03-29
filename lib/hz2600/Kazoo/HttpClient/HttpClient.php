<?php

namespace Kazoo\HttpClient;

use \Kazoo\SDK;
use \Kazoo\HttpClient\Message\Response;

use \Kazoo\Api\Exception\ApiException;
use \Kazoo\Api\Exception\Validation;
use \Kazoo\Api\Exception\RateLimit;
use \Kazoo\Api\Exception\Billing;
use \Kazoo\Api\Exception\Conflict;
use \Kazoo\AuthToken\Exception\Unauthenticated;
use \Kazoo\AuthToken\Exception\Unauthorized;
use \Kazoo\HttpClient\Exception\HttpException;
use \Kazoo\HttpClient\Exception\NotFound;
use \Kazoo\HttpClient\Exception\InvalidMethod;

use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

/**
 * Performs requests on Kazoo API.
 *
 */
class HttpClient implements HttpClientInterface
{
    /**
     *
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     *
     * @var \Kazoo\SDK
     */
    private $sdk;

    /**
     *
     * @var array
     */
    private $headers = array();

    /**
     *
     * @param \Kazoo\SDK $sdk
     */
    public function __construct(SDK $sdk) {
        $this->setSDK($sdk);
        $sdk = $this->getSDK();
        $options = $sdk->getOptions();

        $handler = HandlerStack::create();
        $handler->push(Middleware::mapRequest(function (Request $request) {
            $sdk = $this->getSDK();
            $sdk->logEntity("request", $request);
            $token = $sdk->getAuthToken()->getToken();
            return $request->withHeader('X-Auth-Token', $token);
        }));
        $handler->push(Middleware::mapResponse(function (GuzzleResponse $guzzleResponse) {
            $response = new Response($guzzleResponse);
            $this->getSDK()->logEntity("response", $response);
            $code = $response->getStatusCode();
            switch ($code) {
            case 400:
                throw new Validation($response);
            case 401:
                // invalid creds
                throw new Unauthenticated($response);
            case 402:
                // not enough credit
                throw new Billing($response);
            case 403:
                // forbidden
                throw new Unauthorized($response);
            case 404:
                // not found
                throw new NotFound($response);
            case 405:
                // invalid method
                throw new InvalidMethod($response);
            case 409:
                // conflicting documents
                throw new Conflict($response);
            case 429:
                // too many requests
                throw new RateLimit($response);
            default:
                if ($code >= 400 && $code < 500) {
                    throw new ApiException($response);
                } else if ($code > 500) {
                    throw new HttpException($response);
                }
            }
            return $guzzleResponse;
        }));
        $options['handler'] = $handler;
        $this->setClient(new GuzzleClient($options));
    }

    /**
     *
     * @var array $headers
     */
    public function setHeaders(array $headers) {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Reset headers to the SDK options
     *
     */
    public function resetHeaders() {
        $headers = $this->getSDK()->getOption('headers');
        $this->headers = array(
            'Accept' => $headers['accept'],
            'Content-Type' => $headers['content_type'],
            'User-Agent' => $headers['user_agent'],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function get($uri, array $parameters = array(), array $headers = array()) {
        return $this->request($uri, null, 'GET', $headers, array('query' => $parameters));
    }

    /**
     * {@inheritDoc}
     */
    public function post($uri, $content = null, array $headers = array()) {
        return $this->request($uri, $content, 'POST', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function patch($uri, $content = null, array $headers = array()) {
        return $this->request($uri, $content, 'PATCH', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($uri, $content = null, array $headers = array()) {
        return $this->request($uri, $content, 'DELETE', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function put($uri, $content, array $headers = array()) {
        return $this->request($uri, $content, 'PUT', $headers);
    }

    /**
     * {@inheritDoc}
     */
    private function request($uri, $content = null, $httpMethod = 'GET', array $headers = array(), array $options = array()) {
        $request = $this->createRequest($httpMethod, $uri, $content, $headers, $options);
        $response = $this->getClient()->send($request);
        return new Response($response);
    }

    private function createRequest($httpMethod, $uri, $content = null, array $headers = array(), array $options = array()) {
        $merged_headers = array_merge($this->headers, $headers);
        return new Request($httpMethod, $uri, $merged_headers, $content);
    }

    /**
     *
     * @return \GuzzleHttp\Client $client
     */
    private function getClient() {
        return $this->client;
    }

    /**
     *
     * @param \GuzzleHttp\Client $client
     */
    private function setClient(GuzzleClient $client) {
        $this->client = $client;
    }

    /**
     *
     * @return \Kazoo\SDK $sdk
     */
    private function getSDK() {
        return $this->sdk;
    }

    /**
     *
     * @param \Kazoo\SDK $sdk
     */
    private function setSDK(SDK $sdk) {
        $this->sdk = $sdk;
    }

    /**
     *
     * @param string $eventName
     * @param string|array $listener
     */
    public function addListener($eventName, $listener) {
        $this->getClient()->getEmitter()->on($eventName, $listener);
    }
}
