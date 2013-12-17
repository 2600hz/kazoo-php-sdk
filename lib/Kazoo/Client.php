<?php

namespace Kazoo;

use stdClass;
use Kazoo\Exception\InvalidArgumentException;
use Kazoo\Exception\AuthenticationException;
use Kazoo\HttpClient\HttpClient;
use Kazoo\HttpClient\HttpClientInterface;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Client as GuzzleClient;

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
        'cache_dir' => null,
        'schema_dir' => null
    );

    /**
     * The Buzz instance used to communicate with Kazoo
     *
     * @var HttpClient
     */
    private $httpClient;
    private $username;
    private $password;
    private $sipRealm;
    private $clientState;
    private $baseAccount;
    private $curAccount;
    private $accounts;
    private $uri_tokens = array("api_version");
    private $uri_token_values = array();

    /**
     * Instantiate a new Kazoo client
     *
     * @param null|HttpClientInterface $httpClient Kazoo http client
     */
    public function __construct($username, $password, $sipRealm, $options = null, $clientState = null, HttpClientInterface $httpClient = null) {
        $this->httpClient = $httpClient;
        $this->username = $username;
        $this->password = $password;
        $this->sipRealm = $sipRealm;

        $this->options['schema_dir'] = dirname(__DIR__) . "/../schemas";

        foreach ($options as $option_key => $option_val) {
            $this->options[$option_key] = $option_val;
        }

        $this->options['base_url'] = $this->options['base_url'] . "/v{api_version}";

        $this->addUriTokenValue('api_version', $this->getOption('api_version'));
        
        if (!is_null($this->clientState)) {
            $this->setClientState($clientState);
        } else {
            $this->setupClientState();
        }
        
        $this->setAuthToken();
        $this->setupAccounts();
    }

    /**
     * 
     * @throws AuthenticationException
     */
    private function setupClientState() {

        $payload = new stdClass();
        $payload->data = new stdClass();
        $payload->data->credentials = md5($this->username . ":" . $this->password);
        $payload->data->realm = $this->sipRealm;
        $tokenizedUri = $this->getTokenizedUri($this->options['base_url']);
        $client = new GuzzleClient($tokenizedUri);
        $headers = array("Content-Type" => "application/json", "Accept" => "application/json");
        $request = $client->put("user_auth", $headers, json_encode($payload));

        try {
            $response = $request->send();
            switch ($response->getStatusCode()) {
                case 200:
                    $this->clientState = json_decode($response->getBody());
                    break;
                default:
                    $message = $response->getStatusCode() . " " . $response->getReasonPhrase() . " " . $response->getProtocol() . $response->getProtocolVersion();
                    throw new AuthenticationException($message);
            }
        } catch (ClientErrorResponseException $e) {
            die($e->getMessage());
        } catch (AuthenticationException $e) {
            die($e->getMessage());
        }
    }
    
    private function setupAccounts(){        
        $this->accounts = new \Kazoo\Api\Resource\Accounts($this, "/accounts");
        $account = $this->accounts->retrieve($this->getClientState()->data->account_id);
        $this->baseAccount = $account;
        $this->setCurrentAccountContext($account);
    }
    
    public function addUriToken($variable){
        if(!in_array($variable, $this->uri_tokens)){
            $this->uri_tokens[] = $variable;
        }
    }
    
    public function addUriTokenValue($token, $value){
        $this->uri_token_values[$token] = $value;
    }
    
    private function getTokenizedUri($uri){
        
        $tokenized_uri = $uri;
        foreach($this->uri_tokens as $token){
            $pattern = "/\{".$token."\}/";
            if(array_key_exists($token, $this->uri_token_values)){
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
     * @param \Kazoo\Api\Data\Entity\Account $account
     */
    public function setCurrentAccountContext(\Kazoo\Api\Data\Entity\Account $account){
        $this->addUriTokenValue('account_id', $account->id);
        $this->curAccount = $account;
    }
    
    public function getAccountContext(){
        return $this->curAccount;
    }

    /**
     * 
     * @param stdClass $clientState
     */
    private function setClientState(stdClass $clientState) {
        $this->clientState = $clientState;
    }

    /**
     * 
     * @return type
     */
    public function getClientState() {
        return $this->clientState;
    }

    /**
     * 
     * @return type
     */
    public function getAuthToken() {
        return $this->getClientState()->auth_token;
    }

    /**
     * Authenticate a user for all next requests
     *
     * @param string      $token Kazoo auth token
     *
     */
    private function setAuthToken() {
        $this->getHttpClient()->authenticate($this->getAuthToken());
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

        if ('api_version' == $name && !in_array($value, array('v3', 'beta'))) {
            throw new InvalidArgumentException(sprintf('Invalid API version ("%s"), valid are: %s', $name, implode(', ', array('v3', 'beta'))));
        }

        $this->options[$name] = $value;
    }
    
    public function __call($name, $arguments) {
        
        $base_headers = array("Content-Type" => "application/json", "Accept" => "application/json");
        
        $uri = (isset($arguments[0]) ? $arguments[0] : '');
        $parameters = (isset($arguments[1]) ? $arguments[1] : array());
        $requestHeaders = (isset($arguments[2]) ? array_merge($base_headers, $arguments[2]) : $base_headers);
        
        $this->getTokenizedUri($uri);

        switch (strtolower($name)) {
            case 'accounts':
                return $this->accounts;
                break;
            case 'account':
                return $this->getCurrentAccount();
                break;
            case 'retrieve':
            case 'get':
                return $this->get($uri, $parameters, $requestHeaders);
                break;
            case 'update':
            case 'post':
                return $this->post($uri, $parameters, $requestHeaders);
                break;
            case 'create':
            case 'put':
                return $this->put($uri, $parameters, $requestHeaders);
                break;
            case 'delete':
                return $this->delete($uri, $parameters, $requestHeaders);
                break;
        }
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
        $response = $this->getHttpClient()->get($path, $parameters, $requestHeaders);

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
        $response = $this->getHttpClient()->post(
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
        $response = $this->getHttpClient()->patch(
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
        $response = $this->getHttpClient()->put(
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
        $response = $this->getHttpClient()->delete(
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
