<?php

namespace Kazoo;

use \BadFunctionCallException;

use Kazoo\Common\Log;
use Kazoo\Common\ChainableInterface;
use Kazoo\Common\Exception\InvalidArgument;
use Kazoo\Common\Exception\InvalidUri;
use Kazoo\AuthToken\AuthTokenInterface;
use Kazoo\AuthToken\Exception\Unauthenticated;
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
    private $auth_token;

    /**
     *
     * @var \Kazoo\HttpClient\HttpClientInterface
     */
    private $http_client;

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
     * @param \Kazoo\AuthToken\AuthTokenInterface $auth_token
     * @param array $options
     */
    public function __construct(AuthTokenInterface $auth_token, array $options = array()) {
        if (is_null($this->options['schema_dir'])) {
            $this->options['schema_dir'] = dirname(__DIR__) . "/../schemas";
        }

        foreach ($options as $option_key => $option_val) {
            $this->options[$option_key] = $option_val;
        }

        $this->options['base_url'] = $this->options['base_url'] . "/v{api_version}";

        $this->setHttpClient(new httpClient($this));

        $this->setTokenValue('api_version', $this->getOption('api_version'));

        $this->setAuthToken($auth_token);
    }

    /**
     *
     *
     */
    public function __call($name, $arguments) {
        $collection_name = '\\Kazoo\\Api\\Collection\\' . $name;
        if (@class_exists($collection_name)) {
            return new $collection_name($this, $arguments);
        }

        $entity_name = '\\Kazoo\\Api\\Entity\\' . $name;
        if (@class_exists($entity_name)) {
            return new $entity_name($this, $arguments);
        }

        $backtrace = debug_backtrace();
        $filename = $backtrace[0]['file'];
        $line = $backtrace[0]['line'];
        $class_name = get_class($this);

        $message = "Call to undefined method $class_name::$name in $filename on line $line";
        throw new BadFunctionCallException($message);
    }

    /**
     *
     * @return \Kazoo\HttpClient\HttpClientInterface
     */
    public function getHttpClient() {
        return $this->http_client;
    }

    /**
     *
     * @params \Kazoo\HttpClient\HttpClientInterface
     */
    public function setHttpClient(HttpClientInterface $http_client) {
        $this->http_client = $http_client;
    }

    /**
     *
     * @return \Kazoo\AuthToken\AuthTokenInterface
     */
    public function getAuthToken() {
        return $this->auth_token;
    }

    /**
     *
     * @params \Kazoo\AuthToken\AuthTokenInterface
     */
    public function setAuthToken(AuthTokenInterface $auth_token) {
        $this->auth_token = $auth_token;
        $this->auth_token->setSDK($this);
    }

    /**
     *
     * @return mixed
     */
    public function getOptions() {
        return $this->options;
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
            $token_values = $this->getTokenValues();
        }

        foreach ($tokens[1] as $token) {
            $pattern = "/\{" . $token . "\}/";
            $value = null;

            if (array_key_exists($token, $token_values)) {
                $value = $token_values[$token];
            }

            if (empty($value) && $token == 'account_id') {
                $value = $this->auth_token->getAccountId();
            }

            if (is_null($value)) {
                throw new InvalidUri("Missing uri token value for " . $token);
            }

            // TODO: urlencode $value?
            $uri = preg_replace($pattern, $value, $uri);
        }

        return $uri;
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $tokenizedUri   Request path.
     * @param array  $parameters     GET parameters.
     * @param array  $requestHeaders Request Headers.
     * @return \Kazoo\HttpClient\Message\Response
     */
    public function get($uri, array $parameters = array(), $requestHeaders = array()) {
        try {
            return $this->executeGet($uri, $parameters, $requestHeaders);
        } catch (Unauthenticated $e) {
            $this->auth_token->reset();
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
     * @param string $uri            Request path.
     * @param array  $content        Post request content
     * @param array  $requestHeaders Request headers.
     * @return \Kazoo\HttpClient\Message\Response
     */
    public function post($uri, $content, $requestHeaders = array()) {
        try {
            return $this->executePost($uri, $content, $requestHeaders);
        } catch (Unauthenticated $e) {
            $this->auth_token->reset();
            return $this->executePost($uri, $content, $requestHeaders);
        }
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string $uri            Request path.
     * @param array  $content        Post body
     * @param array  $requestHeaders Request headers.
     * @return \Kazoo\HttpClient\Message\Response
     */
    public function postRaw($uri, $content, $requestHeaders = array()) {
        try {
            return $this->executePost($uri, $content, $requestHeaders);
        } catch (Unauthenticated $e) {
            $this->auth_token->reset();
            return $this->executePost($uri, $content, $requestHeaders);
        }
    }

    /**
     *
     *
     */
    private function executePost($uri, $content, $requestHeaders) {
        return $this->getHttpClient()->post($uri, $content, $requestHeaders);
    }

    /**
     * Send a PATCH request with JSON-encoded parameters.
     *
     * @param string $uri            Request path.
     * @param array  $content        Patch request content
     * @param array  $requestHeaders Request headers.
     * @return \Kazoo\HttpClient\Message\Response
     */
    public function patch($uri, $content, $requestHeaders = array()) {
        try {
            return $this->executePatch($uri, $content, $requestHeaders);
        } catch (Unauthenticated $e) {
            $this->auth_token->reset();
            return $this->executePatch($uri, $content, $requestHeaders);
        }
    }

    /**
     *
     *
     */
    private function executePatch($uri, $content, $requestHeaders) {
        return $this->getHttpClient()->patch($uri, $content, $requestHeaders);
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     *
     * @param string $uri            Request path.
     * @param array  $content        Put request content
     * @param array  $requestHeaders Request headers.
     * @return \Kazoo\HttpClient\Message\Response
     */
    public function put($uri, $content, $requestHeaders = array()) {
        try {
            return $this->executePut($uri, $content, $requestHeaders);
        } catch (Unauthenticated $e) {
            $this->auth_token->reset();
            return $this->executePut($uri, $content, $requestHeaders);
        }
    }

    /**
     *
     *
     */
    private function executePut($uri, $content, $requestHeaders) {
        return $this->getHttpClient()->put($uri, $content, $requestHeaders);
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     *
     * @param string $uri            Request path.
     * @param array  $content        Delete request content
     * @param array  $requestHeaders Request headers.
     * @return \Kazoo\HttpClient\Message\Response
     */
    public function delete($uri, $content = null, $requestHeaders = array()) {
        try {
            return $this->executeDelete($uri, $content, $requestHeaders);
        } catch (Unauthenticated $e) {
            $this->auth_token->reset();
            return $this->executeDelete($uri, $content, $requestHeaders);
        }
    }

    /**
     *
     *
     */
    private function executeDelete($uri, $content, $requestHeaders) {
        return $this->getHttpClient()->delete($uri, $content, $requestHeaders);
    }

    /**
     *
     * @param string $name
     * @param mixed  $value
     */
    protected function setTokenValue($name, $value) {
        if (is_null($value)) {
            unset($this->token_values[$name]);
        } else {
            $this->token_values[$name] = $value;
        }
    }
}
