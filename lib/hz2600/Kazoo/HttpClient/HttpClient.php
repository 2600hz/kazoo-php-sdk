<?php

namespace Kazoo\HttpClient;

use \Kazoo\SDK;
use \Kazoo\HttpClient\Message\Response;
use \Kazoo\HttpClient\Listener\ErrorListener;
use \Kazoo\HttpClient\Listener\AuthListener;

use \GuzzleHttp\Client as GuzzleClient;
use \GuzzleHttp\ClientInterface;

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
        $this->setClient(new GuzzleClient($sdk->getOptions()));
        $this->addListener('before', array(new AuthListener($sdk), 'onRequestBeforeSend'));
        $this->addListener('error', array(new ErrorListener(), 'onRequestError'));
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
        $options['body'] = $content;
        $options['headers'] = $merged_headers;
        return $this->getClient()->createRequest($httpMethod, $uri, $options);
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
