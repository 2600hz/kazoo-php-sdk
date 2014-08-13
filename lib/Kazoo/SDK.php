<?php

namespace Kazoo;

require_once dirname(__FILE__) . "/../../vendor/autoload.php";

use stdClass;
use Kazoo\Api\ChainableInterface;
use Kazoo\Exception\InvalidArgumentException;
use Kazoo\Exception\AuthenticationException;
use Kazoo\AuthToken\AuthTokenInterface;
use Kazoo\HttpClient\HttpClient;
use Kazoo\HttpClient\HttpClientInterface;
use Kazoo\HttpClient\Message\ResponseMediator;
use Kazoo\Common\Log;

/**
 * PHP Kazoo SDK
 *
 * Website: http://github.com/2600hz/kazoo-php-sdk
 */
class SDK implements ChainableInterface
{

    /**
     * @var array
     */
    private $options = array(
        'base_url' => 'http://127.0.0.1:8000',
        'user_agent' => 'kazoo-php-sdk (http://github.com/2600hz/kazoo-php-sdk)',
        'timeout' => 10,
        'api_limit' => 5000,
        'api_version' => '1',
        'log_type' => null,
        'log_file' => null,
        'cache_dir' => null,
        'schema_dir' => null
    );

    const GREGORIAN_OFFSET = 62167219200;
    const DATE_FORMAT = 'Y-m-d';

    /**
     *
     * @var AuthToken\AuthTokenInterface
     */
    private $authToken;

    /**
     * The Buzz instance used to communicate with Kazoo
     *
     * @var HttpClient
     */
    private $httpClient;

    /**
     *
     * @var array
     */
    private $baseHeaders = array("Content-Type" => "application/json", "Accept" => "application/json");

    /**
     *
     * @var array
     */
    private $token_values = array();


    /* CHAINABLE INTERFACE */
    public function getSDK() {
        return $this;
    }

    public function getTokenUri() {
        return $this->getOption('base_url');
    }

    public function getTokenValues() {
        return $this->token_values;
    }

    public function getBaseHeaders() {
        return $this->baseHeaders;
    }
    /* END OF CHAINABLE INTERFACE */

    /**
     *
     *
     * @param \Kazoo\AuthToken\AuthTokenInterface $authToken
     * @param null|array $options
     * @param null|\Kazoo\HttpClient\HttpClientInterface $httpClient
     */
    public function __construct(AuthTokenInterface $authToken, $options = array(), HttpClientInterface $httpClient = null) {
        $this->httpClient = $httpClient;

        if (is_null($this->options['schema_dir'])) {
            $this->options['schema_dir'] = dirname(__DIR__) . "/../schemas";
        }

        foreach ($options as $option_key => $option_val) {
            $this->options[$option_key] = $option_val;
        }

        $this->options['base_url'] = $this->options['base_url'] . "/v{api_version}";

        $this->setToken('api_version', $this->getOption('api_version'));

        $this->setHeaders($this->getBaseHeaders());

        $this->setAuthToken($authToken);
    }

    public function getTokenizedUri($uri, $token_values = null) {
        if (preg_match_all("/\{(\w+)\}/", $uri, $tokens) === 0) {
            return $uri;
        }

        if (is_null($token_values)) {
            $token_values = $this->token_values;
        }

        foreach ($tokens[1] as $token) {
            $pattern = "/\{" . $token . "\}/";
            $value = null;

            if (array_key_exists($token, $token_values)) {
                $value = $token_values[$token];
            } else if ($token == 'account_id') {
                $value = $this->authToken->getAccountId();
            }

            if (empty($value)) {
                throw new Exception\UriTokenException("Missing uri token value for token: " . $token);
            }

            $uri = preg_replace($pattern, $value, $uri);
        }

        return $uri;
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient() {
        if (null === $this->httpClient) {
            $this->httpClient = new HttpClient($this->options);
        }

        return $this->httpClient;
    }

    /**
     * @param HttpClientInterface $httpClient
     */
    public function setHttpClient(HttpClientInterface $httpClient) {
        $this->httpClient = $httpClient;
    }

    public function getAuthToken() {
        return $this->authToken;
    }

    public function setAuthToken(AuthTokenInterface $authToken) {
        $this->authToken = $authToken;
        $this->authToken->setSDK($this);
    }
    /**
     * Clears used headers
     */
    public function clearHeaders() {
        $this->getHttpClient()->clearHeaders();
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers) {
        $this->getHttpClient()->setHeaders($headers);
    }

    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function getOption($name) {
        if (!array_key_exists($name, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $name));
        }

        return $this->options[$name];
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     */
    public function setOption($name, $value) {
        if (!array_key_exists($name, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $name));
        }

        if ($name == 'api_version' && !in_array($value, array('1', '2'))) {
            throw new InvalidArgumentException(sprintf('Invalid API version ("%s"), valid are: %s', $name, implode(', ', array('v3', 'beta'))));
        }

        $this->options[$name] = $value;
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $tokenizedUri              Request path.
     * @param array $parameters         GET parameters.
     * @param array $requestHeaders     Request Headers.
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function get($uri, array $parameters = array(), $requestHeaders = array()) {
        try {
            return $this->executeGet($uri, $parameters, $requestHeaders);
        } catch (AuthenticationException $e) {
            $this->authToken->reset();
            return $this->executeGet($uri, $parameters, $requestHeaders);
        }
    }

    private function executeGet($uri, $parameters, $requestHeaders) {
        try {
            $response = $this->getHttpClient()->get($uri, $parameters, $requestHeaders);
            return ResponseMediator::getContent($response);
        } catch (ErrorException $e) {
            Log::addCritical($e->getMessage());
        } catch (RuntimeException $e) {
            Log::addCritical($e->getMessage());
        }
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     *
     * @param string $uri              Request path.
     * @param array $parameters         POST parameters to be JSON encoded.
     * @param array $requestHeaders     Request headers.
     */
    public function post($uri, $payload, $requestHeaders = array()) {
        $shell = new stdClass();
        $shell->data = $payload;
        $body = $this->createJsonBody($shell);
        try {
            return $this->executePost($uri, $body, $requestHeaders);
        } catch (AuthenticationException $e) {
            $this->authToken->reset();
            return $this->executePost($uri, $body, $requestHeaders);
        }
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string $uri              Request path.
     * @param $body                     Request body.
     * @param array $requestHeaders     Request headers.
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function postRaw($uri, $body, $requestHeaders = array()) {
        try {
            return $this->executePost($uri, $body, $requestHeaders);
        } catch (AuthenticationException $e) {
            $this->authToken->reset();
            return $this->executePost($uri, $body, $requestHeaders);
        }
    }

    private function executePost($uri, $body, $requestHeaders) {
        try {
            $response = $this->getHttpClient()->post($uri, $body, $requestHeaders);
            return ResponseMediator::getContent($response);
        } catch (ErrorException $e) {
            Log::addCritical($e->getMessage());
        } catch (RuntimeException $e) {
            Log::addCritical($e->getMessage());
        }
    }

    /**
     * Send a PATCH request with JSON-encoded parameters.
     *
     * @param string $uri      Request path.
     * @param array $parameters         POST parameters to be JSON encoded.
     * @param array $requestHeaders     Request headers.
     */
    public function patch($uri, $payload, $requestHeaders = array()) {
        $shell = new stdClass();
        $shell->data = $payload;
        $body = $this->createJsonBody($payload);
        try {
            return $this->executePatch($uri, $body, $requestHeaders);
        } catch (AuthenticationException $e) {
            $this->authToken->reset();
            return $this->executePatch($uri, $body, $requestHeaders);
        }
    }

    private function executePatch($uri, $body, $requestHeaders) {
        try {
            $response = $this->getHttpClient()->patch($uri, $body, $requestHeaders);
            return ResponseMediator::getContent($response);
        } catch (ErrorException $e) {
            Log::addCritical($e->getMessage());
        } catch (RuntimeException $e) {
            Log::addCritical($e->getMessage());
        }
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     *
     * @param string $uri  Request path.
     * @param $parameters           POST parameters to be JSON encoded.
     * @param array $requestHeaders Request headers.
     */
    public function put($uri, $payload, $requestHeaders = array()) {
        $shell = new stdClass();
        $shell->data = $payload;
        $body = $this->createJsonBody($shell);
        try {
            return $this->executePut($uri, $body, $requestHeaders);
        } catch (AuthenticationException $e) {
            $this->authToken->reset();
            return $this->executePut($uri, $body, $requestHeaders);
        }
    }

    private function executePut($uri, $body, $requestHeaders) {
        try {
            $response = $this->getHttpClient()->put($uri, $body, $requestHeaders);
            return ResponseMediator::getContent($response);
        } catch (ErrorException $e) {
            Log::addCritical($e->getMessage());
        } catch (RuntimeException $e) {
            Log::addCritical($e->getMessage());
        }
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     *
     * @param string $path              Request path.
     * @param array $parameters         POST parameters to be JSON encoded.
     * @param array $requestHeaders     Request headers.
     */
    public function delete($uri, array $parameters = null, $requestHeaders = array()) {
        $body = $this->createJsonBody($parameters);
        try {
            return $this->executeDelete($uri, $body, $requestHeaders);
        } catch (AuthenticationException $e) {
            $this->authToken->reset();
            return $this->executeDelete($uri, $body, $requestHeaders);
        }
    }

    private function executeDelete($uri, $body, $requestHeaders) {
        try {
            $response = $this->getHttpClient()->delete($uri, $body, $requestHeaders);
            return ResponseMediator::getContent($response);
        } catch (ErrorException $e) {
            Log::addCritical($e->getMessage());
        } catch (RuntimeException $e) {
            Log::addCritical($e->getMessage());
        }
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @param array $parameters   Request parameters
     * @return null|string
     */
    protected function createJsonBody($parameters = null) {
        return ((is_null($parameters)) ? null : json_encode($parameters) );
    }

    public function __call($name, $arguments) {
        $collectionName = '\\Kazoo\\Api\\Collection\\' . $name;
        if (class_exists($collectionName)) {
            return new $collectionName($this, $arguments);
        }

        $entityName = '\\Kazoo\\Api\\Entity\\' . $name;
        if (class_exists($entityName)) {
            return new $entityName($this, $arguments);
        }
    }

    protected function setToken($token, $value) {
        if (is_null($value)) {
            unset($this->token_values[$token]);
        } else {
            $this->token_values[$token] = $value;
        }
    }
}
