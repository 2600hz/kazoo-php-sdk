<?php

namespace Kazoo\HttpClient;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Kazoo\Exception\ErrorException;
use Kazoo\Exception\RuntimeException;
use Kazoo\HttpClient\Listener\AuthListener;
use Kazoo\HttpClient\Listener\ErrorListener;

/**
 * Performs requests on Kazoo API.
 *
 */
class HttpClient implements HttpClientInterface {

    protected $options = array(
        'user_agent' => 'kazoo-php-sdk (http://github.com/2600hz/kazoo-php-sdk)',
        'timeout' => 10,
        'api_limit' => 5000,
        'api_version' => 'v1',
        'cache_dir' => null
    );
    protected $headers = array();
    private $lastResponse;
    private $lastRequest;

    /**
     * @param array           $options
     * @param ClientInterface $client
     */
    public function __construct(array $options = array(), ClientInterface $client = null) {
        $this->options = array_merge($this->options, $options);
        $client = $client ? : new GuzzleClient($this->options['base_url'], $this->options);
        $this->client = $client;

        $this->addListener('request.error', array(new ErrorListener($this->options), 'onRequestError'));
        $this->clearHeaders();
    }

    /**
     * {@inheritDoc}
     */
    public function setOption($name, $value) {
        $this->options[$name] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function setHeaders(array $headers) {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Clears used headers
     */
    public function clearHeaders() {
        $this->headers = array(
            'User-Agent' => sprintf('%s', $this->options['user_agent'])
        );
    }

    public function addListener($eventName, $listener) {
        $this->client->getEventDispatcher()->addListener($eventName, $listener);
    }

    /**
     * {@inheritDoc}
     */
    public function get($path, array $parameters = array(), array $headers = array()) {
        return $this->request($path, null, 'GET', $headers, array('query' => $parameters));
    }

    /**
     * {@inheritDoc}
     */
    public function post($path, $body = null, array $headers = array()) {
        return $this->request($path, $body, 'POST', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function patch($path, $body = null, array $headers = array()) {
        return $this->request($path, $body, 'PATCH', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($path, $body = null, array $headers = array()) {
        return $this->request($path, $body, 'DELETE', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function put($path, $body, array $headers = array()) {
        return $this->request($path, $body, 'PUT', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function request($path, $body = null, $httpMethod = 'GET', array $headers = array(), array $options = array()) {

        $request = $this->createRequest($httpMethod, $path, $body, $headers, $options);
        $request->addHeaders($headers);

        try {
            $response = $this->client->send($request);
        } catch (\LogicException $e) {
            throw new ErrorException($e->getMessage());
        } catch (\RuntimeException $e) {
            throw new RuntimeException($e->getMessage());
        }

        $this->lastRequest = $request;
        $this->lastResponse = $response;

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate($token) {
        $this->addListener('request.before_send', array(
            new AuthListener($token), 'onRequestBeforeSend'
        ));
    }

    /**
     * @return Request
     */
    public function getLastRequest() {
        return $this->lastRequest;
    }

    /**
     * @return Response
     */
    public function getLastResponse() {
        return $this->lastResponse;
    }

    protected function createRequest($httpMethod, $path, $body = null, array $headers = array(), array $options = array()) {
        return $this->client->createRequest(
                        $httpMethod, $path, array_merge($this->headers, $headers), $body, $options
        );
    }

}
