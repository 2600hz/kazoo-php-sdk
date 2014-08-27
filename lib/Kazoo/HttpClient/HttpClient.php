<?php

namespace Kazoo\HttpClient;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

use Guzzle\Log\MonologLogAdapter;
use Monolog\Logger;
use Guzzle\Plugin\Log\LogPlugin;
use Guzzle\Log\MessageFormatter;

use Kazoo\Exception\ErrorException;
use Kazoo\Exception\RuntimeException;
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
        'api_version' => '1',
        'log_type' => null,
        'log_file' => null,
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

        $logger = null;
        switch($this->options['log_type']){
            case "file":
                $logger = new Logger('sdk_logger');
                $logger->pushHandler(new \Monolog\Handler\StreamHandler($this->options['log_file'], LOGGER::DEBUG));
                break;
            case "stdout":
                $logger = new Logger('sdk_logger');
                $logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', LOGGER::DEBUG));
                break;
            default:
                $logger = null;
        }

        if(!is_null($logger)){
            $adapter = new MonologLogAdapter($logger);
            $logPlugin = new LogPlugin($adapter, MessageFormatter::DEBUG_FORMAT);
            $client->addSubscriber($logPlugin);
        }

        $this->addListener('request.error', array(new ErrorListener($this->options), 'onRequestError'));
        $this->clearHeaders();
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

    protected function createRequest($httpMethod, $path, $body = null, array $headers = array(), array $options = array()) {
        $merged_headers = array_merge($this->headers, $headers);
        return $this->client->createRequest($httpMethod, $path, $merged_headers, $body, $options);
    }
}
