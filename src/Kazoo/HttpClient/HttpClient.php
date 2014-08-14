<?php

namespace Kazoo\HttpClient;

use Kazoo\SDK;
use Kazoo\HttpClient\Message\Response;
use Kazoo\HttpClient\Listener\ErrorListener;
use Kazoo\HttpClient\Listener\AuthListener;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\ClientInterface;

/**
 * Performs requests on Kazoo API.
 *
 */
class HttpClient implements HttpClientInterface
{
    /**
     *
     * @var \Guzzle\Http\Client
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
        $this->setClient(new GuzzleClient('', $this->options));
        $this->addListener('request.before_send', array(new AuthListener($sdk), 'onRequestBeforeSend'));
        $this->addListener('request.error', array(new ErrorListener(), 'onRequestError'));
        $this->resetHeaders();
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
        $sdk = $this->getSDK();
        $this->headers = array(
            'Accept' => $sdk->getOption('accept'),
            'Content-Type' => $sdk->getOption('content_type'),
            'User-Agent' => $sdk->getOption('user_agent'),
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
    public function post($uri, $body = null, array $headers = array()) {
        return $this->request($uri, $body, 'POST', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function patch($uri, $body = null, array $headers = array()) {
        return $this->request($uri, $body, 'PATCH', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($uri, $body = null, array $headers = array()) {
        return $this->request($uri, $body, 'DELETE', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function put($uri, $body, array $headers = array()) {
        return $this->request($uri, $body, 'PUT', $headers);
    }

    /**
     * {@inheritDoc}
     */
    private function request($uri, $body = null, $httpMethod = 'GET', array $headers = array(), array $options = array()) {
        $request = $this->createRequest($httpMethod, $uri, $body, $headers, $options);
        $response = $this->getClient()->send($request);
        return new Response($response);
    }

    private function createRequest($httpMethod, $uri, $body = null, array $headers = array(), array $options = array()) {
        $merged_headers = array_merge($this->headers, $headers);
        return $this->getClient()->createRequest($httpMethod, $uri, $merged_headers, $body, $options);
    }

    /**
     *
     * @return \Guzzle\Http\Client $client
     */
    private function getClient() {
        return $this->client;
    }

    /**
     *
     * @param \Guzzle\Http\Client $client
     */
    private function setClient(Client $client) {
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
        $this->getClient()->getEventDispatcher()->addListener($eventName, $listener);
    }
}
