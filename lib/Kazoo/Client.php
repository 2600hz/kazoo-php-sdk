<?php

namespace Kazoo;

require_once dirname(__FILE__) . "/../../vendor/autoload.php";

use stdClass;
use Kazoo\Exception\InvalidArgumentException;
use Kazoo\Exception\AuthenticationException;
use Kazoo\HttpClient\HttpClient;
use Kazoo\HttpClient\HttpClientInterface;
use Kazoo\HttpClient\Message\ResponseMediator;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * PHP Kazoo SDK
 *
 * Website: http://github.com/2600hz/kazoo-php-sdk
 */
class Client {

    /**
     * @var array
     */
    private $options = array(
        'base_url' => 'https://127.0.0.1:8000',
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
     * number of items per page
     *
     * @var null|int
     */
    protected $perPage;

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
     * @var \Monolog\Logger
     */
    private $logger;

    /**
     *
     * @var stdClass
     */
    private $clientState;

    /**
     *
     * @var \Kazoo\Api\Data\Entity\Account
     */
    private $baseAccount;

    /**
     *
     * @var \Kazoo\Api\Data\Entity\Account
     */
    private $curAccount;

    /**
     *
     * @var array
     */
    private $baseHeaders = array("Content-Type" => "application/json", "Accept" => "application/json");

    /**
     *
     * @var \Kazoo\Api\Resource\Accounts
     */
    private $accounts;

    /**
     *
     * @var \Kazoo\Api\Resource\PhoneNumbers
     */
    private $phone_numbers;

    /**
     *
     * @var array
     */
    private $uri_tokens = array("api_version");

    /**
     *
     * @var array
     */
    private $uri_token_values = array();

    /**
     *
     *
     * @param AuthToken\AuthTokenInterface $authToken
     * @param null|array $options
     * @param null|\Kazoo\HttpClient\HttpClientInterface $httpClient
     */
    public function __construct(AuthToken\AuthTokenInterface $authToken, $options = null, HttpClientInterface $httpClient = null) {
        $this->httpClient = $httpClient;

        if (is_null($this->options['schema_dir'])) {
            $this->options['schema_dir'] = dirname(__DIR__) . "/../schemas";
        }

        foreach ($options as $option_key => $option_val) {
            $this->options[$option_key] = $option_val;
        }

        switch ($this->options['log_type']) {
            case "file":
                $this->logger = new Logger('sdk_logger');
                $this->logger->pushHandler(new StreamHandler($this->options['log_file'], Logger::DEBUG));
                break;
            case "stdout":
                $this->logger = new Logger('sdk_logger');
                $this->logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
                break;
            default:
            case null:
                $this->logger = new Logger('sdk_logger');
                $this->logger->pushHandler(new StreamHandler('php://stdout', Logger::CRITICAL));
        }

        $this->options['base_url'] = $this->options['base_url'] . "/v{api_version}";

        $this->addUriTokenValue('api_version', $this->getOption('api_version'));

        $this->setHeaders($this->getBaseHeaders());

        $this->authToken = $authToken;
        $this->authToken->setClient($this);

        $this->setAccountContext($this->authToken->getAccountId());

        $this->accounts = new \Kazoo\Api\Resource\Accounts($this, "/accounts/{account_id}");
        $this->phone_numbers = new \Kazoo\Api\Resource\GlobalPhoneNumbers($this, "/phone_numbers");
    }

    public function getAuthToken() {
        return $this->authToken;
    }

    public function getLogger() {
        return $this->logger;
    }

    public function getBaseHeaders() {
        return $this->baseHeaders;
    }

    public function addUriToken($variable) {
        if (!in_array($variable, $this->uri_tokens)) {
            $this->uri_tokens[] = $variable;
        }
    }

    public function addUriTokenValue($token, $value) {
        $this->uri_token_values[$token] = $value;
    }

    public function getTokenizedUri($uri) {
        $tokenized_uri = $this->getOption('base_url') . $uri;
        foreach ($this->uri_tokens as $token) {
            $pattern = "/\{" . $token . "\}/";
            if (array_key_exists($token, $this->uri_token_values)) {
                $value = $this->uri_token_values[$token];
            } else {
                throw new Exception\UriTokenException("Missing uri token value for token: " . $token);
            }

            $tokenized_uri = preg_replace($pattern, $value, $tokenized_uri);
        }
        return $tokenized_uri;
    }

    /**
     *
     * @param string $account
     */
    public function setAccountContext($account_id) {
        $this->addUriToken("account_id");
        $this->addUriTokenValue('account_id', $account_id);
        $this->curAccount = $account_id;
    }

    public function getAccountContext() {
        return $this->curAccount;
    }

    public function resetBaseAccountContext() {
        $this->curAccount = $this->authToken->getAccountId();
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
    public function get($path, array $parameters = array(), $requestHeaders = array()) {
        if (null !== $this->perPage && !isset($parameters['per_page'])) {
            $parameters['per_page'] = $this->perPage;
        }
        if (array_key_exists('ref', $parameters) && is_null($parameters['ref'])) {
            unset($parameters['ref']);
        }

        $tokenizedUri = $this->getTokenizedUri($path);

        try {
            return $this->executeGet($tokenizedUri, $parameters, $requestHeaders);
        } catch (AuthenticationException $e) {
            $this->authToken->reset();
            return $this->executeGet($tokenizedUri, $parameters, $requestHeaders);
        }
    }

    private function executeGet($tokenizedUri, $parameters, $requestHeaders) {
        try {
            $response = $this->getHttpClient()->get($tokenizedUri, $parameters, $requestHeaders);
            return ResponseMediator::getContent($response);
        } catch (ErrorException $e) {
            $this->getLogger()->addCritical($e->getMessage());
        } catch (RuntimeException $e) {
            $this->getLogger()->addCritical($e->getMessage());
        }
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     *
     * @param string $path              Request path.
     * @param array $parameters         POST parameters to be JSON encoded.
     * @param array $requestHeaders     Request headers.
     */
    public function post($path, $payload, $requestHeaders = array()) {
        $shell = new stdClass();
        $shell->data = $payload;
        $body = $this->createJsonBody($shell);

        $tokenizedUri = $this->getTokenizedUri($path);

        try {
            return $this->executePost($tokenizedUri, $body, $requestHeaders);
        } catch (AuthenticationException $e) {
            $this->authToken->reset();
            return $this->executePost($tokenizedUri, $body, $requestHeaders);
        }
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string $path              Request path.
     * @param $body                     Request body.
     * @param array $requestHeaders     Request headers.
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function postRaw($path, $body, $requestHeaders = array()) {
        $tokenizedUri = $this->getTokenizedUri($path);

        try {
            return $this->executePost($tokenizedUri, $body, $requestHeaders);
        } catch (AuthenticationException $e) {
            $this->authToken->reset();
            return $this->executePost($tokenizedUri, $body, $requestHeaders);
        }
    }

    private function executePost($tokenizedUri, $body, $requestHeaders) {
        try {
            $response = $this->getHttpClient()->post($tokenizedUri, $body, $requestHeaders);
            return ResponseMediator::getContent($response);
        } catch (ErrorException $e) {
            $this->getLogger()->addCritical($e->getMessage());
        } catch (RuntimeException $e) {
            $this->getLogger()->addCritical($e->getMessage());
        }
    }

    /**
     * Send a PATCH request with JSON-encoded parameters.
     *
     * @param string $path              Request path.
     * @param array $parameters         POST parameters to be JSON encoded.
     * @param array $requestHeaders     Request headers.
     */
    public function patch($path, $payload, $requestHeaders = array()) {
        $shell = new stdClass();
        $shell->data = $payload;
        $body = $this->createJsonBody($payload);

        $tokenizedUri = $this->getTokenizedUri($path);

        try {
            return $this->executePatch($tokenizedUri, $body, $requestHeaders);
        } catch (AuthenticationException $e) {
            $this->authToken->reset();
            return $this->executePatch($tokenizedUri, $body, $requestHeaders);
        }
    }

    private function executePatch($tokenizedUri, $body, $requestHeaders) {
        try {
            $response = $this->getHttpClient()->patch($tokenizedUri, $body, $requestHeaders);
            return ResponseMediator::getContent($response);
        } catch (ErrorException $e) {
            $this->getLogger()->addCritical($e->getMessage());
        } catch (RuntimeException $e) {
            $this->getLogger()->addCritical($e->getMessage());
        }
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     *
     * @param string $path              Request path.
     * @param $parameters         POST parameters to be JSON encoded.
     * @param array $requestHeaders     Request headers.
     */
    public function put($path, $payload, $requestHeaders = array()) {
        $shell = new stdClass();
        $shell->data = $payload;
        $body = $this->createJsonBody($shell);

        $tokenizedUri = $this->getTokenizedUri($path);

        try {
            return $this->executePut($tokenizedUri, $body, $requestHeaders);
        } catch (AuthenticationException $e) {
            $this->authToken->reset();
            return $this->executePut($tokenizedUri, $body, $requestHeaders);
        }
    }

    private function executePut($tokenizedUri, $body, $requestHeaders) {
        try {
            $response = $this->getHttpClient()->put($tokenizedUri, $body, $requestHeaders);
            return ResponseMediator::getContent($response);
        } catch (ErrorException $e) {
            $this->getLogger()->addCritical($e->getMessage());
        } catch (RuntimeException $e) {
            $this->getLogger()->addCritical($e->getMessage());
        }
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     *
     * @param string $path              Request path.
     * @param array $parameters         POST parameters to be JSON encoded.
     * @param array $requestHeaders     Request headers.
     */
    public function delete($path, array $parameters = null, $requestHeaders = array()) {
        $tokenizedUri = $this->getTokenizedUri($path);

        $body = $this->createJsonBody($parameters);

        try {
            return $this->executeDelete($tokenizedUri, $body, $requestHeaders);
        } catch (AuthenticationException $e) {
            $this->authToken->reset();
            return $this->executeDelete($tokenizedUri, $body, $requestHeaders);
        }
    }

    private function executeDelete($tokenizedUri, $body, $requestHeaders) {
        try {
            $response = $this->getHttpClient()->delete($tokenizedUri, $body, $requestHeaders);
            return ResponseMediator::getContent($response);
        } catch (ErrorException $e) {
            $this->getLogger()->addCritical($e->getMessage());
        } catch (RuntimeException $e) {
            $this->getLogger()->addCritical($e->getMessage());
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
        switch (strtolower($name)) {
        case 'phone_numbers':
            return $this->phone_numbers;
            break;
        case 'accounts':
            return $this->accounts;
            break;
        case 'account':
            return $this->getAccountContext();
            break;
        }
    }
}
