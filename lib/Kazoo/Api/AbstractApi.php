<?php

namespace Kazoo\Api;

use Kazoo\Client;
use Kazoo\HttpClient\Message\ResponseMediator;

/**
 * Abstract class for Api classes
 *
 */
abstract class AbstractApi implements ApiInterface {

    /**
     * The client
     *
     * @var Client
     */
    protected $client;

    /**
     * number of items per page (Kazoo pagination)
     *
     * @var null|int
     */
    protected $perPage;

    /**
     *
     * @var type 
     */
    protected $_resourceNoun;

    /**
     *
     * @var type 
     */
    protected $_schema_name;

    /**
     *
     * @var type 
     */
    protected $_schema_json;

    /**
     * @param Client $client
     */
    public function __construct(Client $client) {
        $this->client = $client;
    }

    protected function setResourceNoun($resourceNoun) {
        $this->_resourceNoun = $resourceNoun;
    }

    protected function getResourceNoun() {
        return $this->_resourceNoun;
    }

    public function setSchemaName($name) {
        $this->_schema_name = $name;
        $this->getSchemaJson();
    }

    public function getSchemaJson() {
        $this->_schema_json = file_get_contents($this->client->getOption('schema_dir') . "/" . $this->_schema_name);
        return $this->_schema_json;
    }

    public function __call($name, $arguments) {
        
        $base_headers = array("Content-Type" => "application/json", "Accept" => "application/json");
        
        $uri = (isset($arguments[0]) ? $arguments[0] : '');
        $parameters = (isset($arguments[1]) ? $arguments[1] : array());
        $requestHeaders = (isset($arguments[2]) ? array_merge($base_headers, $arguments[2]) : $base_headers);

        switch (strtolower($name)) {
            case 'new':
                return JsonSchemaObjectFactory::getNew($this->getResourceNoun(), $this->getSchemaJson());
                break;
            case 'get':
                return $this->get($uri, $parameters, $requestHeaders);
                break;
            case 'post':
                return $this->post($uri, $parameters, $requestHeaders);
                break;
            case 'put':
                return $this->put($uri, $parameters, $requestHeaders);
                break;
            case 'delete':
                return $this->delete($uri, $parameters, $requestHeaders);
                break;
            case 'patch':
                return $this->patch($uri, $parameters, $requestHeaders);
                break;
        }
    }

    public function configure() {
        
    }

    /**
     * @return null|int
     */
    public function getPerPage() {
        return $this->perPage;
    }

    /**
     * @param null|int $perPage
     */
    public function setPerPage($perPage) {
        $this->perPage = (null === $perPage ? $perPage : (int) $perPage);

        return $this;
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $path              Request path.
     * @param array $parameters         GET parameters.
     * @param array $requestHeaders     Request Headers.
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    protected function get($path, array $parameters = array(), $requestHeaders = array()) {
        if (null !== $this->perPage && !isset($parameters['per_page'])) {
            $parameters['per_page'] = $this->perPage;
        }
        if (array_key_exists('ref', $parameters) && is_null($parameters['ref'])) {
            unset($parameters['ref']);
        }
        $response = $this->client->getHttpClient()->get($path, $parameters, $requestHeaders);

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     *
     * @param string $path              Request path.
     * @param array $parameters         POST parameters to be JSON encoded.
     * @param array $requestHeaders     Request headers.
     */
    protected function post($path, array $parameters = array(), $requestHeaders = array()) {
        return $this->postRaw(
                        $path, $this->createJsonBody($parameters), $requestHeaders
        );
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string $path              Request path.
     * @param $body                     Request body.
     * @param array $requestHeaders     Request headers.
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    protected function postRaw($path, $body, $requestHeaders = array()) {
        $response = $this->client->getHttpClient()->post(
                $path, $body, $requestHeaders
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a PATCH request with JSON-encoded parameters.
     *
     * @param string $path              Request path.
     * @param array $parameters         POST parameters to be JSON encoded.
     * @param array $requestHeaders     Request headers.
     */
    protected function patch($path, array $parameters = array(), $requestHeaders = array()) {
        $response = $this->client->getHttpClient()->patch(
                $path, $this->createJsonBody($parameters), $requestHeaders
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     *
     * @param string $path              Request path.
     * @param array $parameters         POST parameters to be JSON encoded.
     * @param array $requestHeaders     Request headers.
     */
    protected function put($path, array $parameters = array(), $requestHeaders = array()) {
        $response = $this->client->getHttpClient()->put(
                $path, $this->createJsonBody($parameters), $requestHeaders
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     *
     * @param string $path              Request path.
     * @param array $parameters         POST parameters to be JSON encoded.
     * @param array $requestHeaders     Request headers.
     */
    protected function delete($path, array $parameters = array(), $requestHeaders = array()) {
        $response = $this->client->getHttpClient()->delete(
                $path, $this->createJsonBody($parameters), $requestHeaders
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @param array $parameters   Request parameters
     * @return null|string
     */
    protected function createJsonBody(array $parameters) {
        return (count($parameters) === 0) ? null : json_encode($parameters, empty($parameters) ? JSON_FORCE_OBJECT : 0);
    }

}
