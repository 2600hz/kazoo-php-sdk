<?php

namespace Kazoo;

require_once dirname(__FILE__) . "/../../vendor/autoload.php";

use stdClass;
use Kazoo\Common\Log;
use Kazoo\Common\ChainableInterface;
use Kazoo\Common\Exception\InvalidArgument;
use Kazoo\Common\Exception\InvalidUri;
use Kazoo\AuthToken\AuthTokenInterface;
use Kazoo\AuthToken\Exception\Unauthorized;
use Kazoo\HttpClient\HttpClient;
use Kazoo\HttpClient\HttpClientInterface;

/**
 * PHP Kazoo SDK
 *
 * Website: http://github.com/2600hz/kazoo-php-sdk
 */
class SDK implements ChainableInterface
{
    const GREGORIAN_OFFSET = 62167219200;
    const DATE_FORMAT = 'Y-m-d';

    /**
     *
     * @var array
     */
    private $options = array(
        'base_url' => 'http://127.0.0.1:8000',
        'user_agent' => 'kazoo-php-sdk (http://github.com/2600hz/kazoo-php-sdk)',
        'content_type' => 'application/json',
        'accept' => 'application/json',
        'timeout' => 10,
        'api_limit' => 5000,
        'api_version' => '1',
        'log_type' => null,
        'log_file' => null,
        'cache_dir' => null,
        'schema_dir' => null
    );

    /**
     *
     * @var \Kazoo\AuthToken\AuthTokenInterface
     */
    private $authToken;

    /**
     *
     * @var \Kazoo\HttpClient\HttpClientInterface
     */
    private $httpClient;

    /**
     *
     * @var array
     */
    private $token_values = array();

    /* CHAINABLE INTERFACE */
    /**
     *
     *
     */
    public function getSDK() {
        return $this;
    }

    /**
     *
     *
     */
    public function getTokenUri() {
        return $this->getOption('base_url');
    }

    /**
     *
     *
     */
    public function getTokenValues() {
        return $this->token_values;
    }
    /* END OF CHAINABLE INTERFACE */

    /**
     *
     *
     * @param \Kazoo\AuthToken\AuthTokenInterface $authToken
     * @param null|array $options
     */
    public function __construct(AuthTokenInterface $authToken, $options = array()) {
        if (is_null($this->options['schema_dir'])) {
            $this->options['schema_dir'] = dirname(__DIR__) . "/../schemas";
        }

        foreach ($options as $option_key => $option_val) {
            $this->options[$option_key] = $option_val;
        }

        $this->options['base_url'] = $this->options['base_url'] . "/v{api_version}";

        $this->setHttpClient(new httpClient($this));

        $this->setTokenValue('api_version', $this->getOption('api_version'));

        $this->setAuthToken($authToken);
    }

    /**
     *
     * @return \Kazoo\HttpClient\HttpClientInterface
     */
    public function getHttpClient() {
        return $this->httpClient;
    }

    /**
     *
     * @params \Kazoo\HttpClient\HttpClientInterface
     */
    public function setHttpClient(HttpClientInterface $httpClient) {
        $this->httpClient = $httpClient;
    }

    /**
     *
     * @return \Kazoo\AuthToken\AuthTokenInterface
     */
    public function getAuthToken() {
        return $this->authToken;
    }

    /**
     *
     * @params \Kazoo\AuthToken\AuthTokenInterface
     */
    public function setAuthToken(AuthTokenInterface $authToken) {
        $this->authToken = $authToken;
        $this->authToken->setSDK($this);
    }

    /**
     *
     * @param string $name
     *
     * @return mixed
     *
     * @throws InvalidArgument
     */
    public function getOption($name) {
        if (!array_key_exists($name, $this->options)) {
            throw new InvalidArgument(sprintf('Undefined option called: "%s"', $name));
        }

        return $this->options[$name];
    }

    /**
     *
     * @param string $name
     * @param mixed  $value
     *
     * @throws InvalidArgument
     */
    public function setOption($name, $value) {
        if (!array_key_exists($name, $this->options)) {
            throw new InvalidArgument(sprintf('Undefined option called: "%s"', $name));
        }

        if ($name == 'api_version' && !in_array($value, array('1', '2'))) {
            throw new InvalidArgument(sprintf('Invalid API version ("%s"), valid are: %s', $name, implode(', ', array('1', '2'))));
        }

        $this->options[$name] = $value;
    }

    /**
     *
     *
     */
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
                throw new InvalidUri("Missing uri token value for " . $token);
            }

            $uri = preg_replace($pattern, $value, $uri);
        }

        return $uri;
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
        } catch (Unauthorized $e) {
            $this->authToken->reset();
            return $this->executeGet($uri, $parameters, $requestHeaders);
        }
    }

    /**
     *
     *
     */
    private function executeGet($uri, $parameters, $requestHeaders) {
        return $this->getHttpClient()->get($uri, $parameters, $requestHeaders);
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
        } catch (Unauthorized $e) {
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
        } catch (Unauthorized $e) {
            $this->authToken->reset();
            return $this->executePost($uri, $body, $requestHeaders);
        }
    }

    /**
     *
     *
     */
    private function executePost($uri, $body, $requestHeaders) {
        return $this->getHttpClient()->post($uri, $body, $requestHeaders);
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
        } catch (Unauthorized $e) {
            $this->authToken->reset();
            return $this->executePatch($uri, $body, $requestHeaders);
        }
    }

    /**
     *
     *
     */
    private function executePatch($uri, $body, $requestHeaders) {
        return $this->getHttpClient()->patch($uri, $body, $requestHeaders);
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
        } catch (Unauthorized $e) {
            $this->authToken->reset();
            return $this->executePut($uri, $body, $requestHeaders);
        }
    }

    /**
     *
     *
     */
    private function executePut($uri, $body, $requestHeaders) {
        return $this->getHttpClient()->put($uri, $body, $requestHeaders);
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
        } catch (Unauthorized $e) {
            $this->authToken->reset();
            return $this->executeDelete($uri, $body, $requestHeaders);
        }
    }

    /**
     *
     *
     */
    private function executeDelete($uri, $body, $requestHeaders) {
        return $this->getHttpClient()->delete($uri, $body, $requestHeaders);
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

    /**
     *
     *
     */
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

    /**
     *
     * @param string $name
     * @param mixed  $value
     */
    protected function setTokenValue($name, $value) {
        if (is_null($value)) {
            unset($this->name_values[$name]);
        } else {
            $this->name_values[$name] = $value;
        }
    }
}
