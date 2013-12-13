<?php

namespace Kazoo;

use stdClass;
use Kazoo\Api\ApiInterface;
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
    private $siprealm;
    private $clientState;
    private $baseAccount;

    /**
     * Instantiate a new Kazoo client
     *
     * @param null|HttpClientInterface $httpClient Kazoo http client
     */
    public function __construct($username, $password, $siprealm, $clientState = null, $options = null, HttpClientInterface $httpClient = null) {
        $this->httpClient = $httpClient;
        $this->username = $username;
        $this->password = $password;
        $this->siprealm = $siprealm;

        $this->options['schema_dir'] = dirname(__DIR__) . "/../schemas";

        if (is_array($options) and !empty($options)) {
            foreach ($options as $option_key => $option_val) {
                $this->options[$option_key] = $option_val;
            }
        }

        $this->options['base_url'] = $this->options['base_url'] . "/v" . $this->options['api_version'];

        if (!is_null($this->clientState)) {
            $this->setClientState($clientState);
        } else {
            $this->setupClientState();
        }

        $this->setAuthToken();
        
        $this->requestBaseAccount();
    }

    /**
     * @param string $name
     *
     * @return ApiInterface
     *
     * @throws InvalidArgumentException
     */
    public function api($noun) {
        switch ($noun) {
            case 'accounts': $api = new Api\Accounts($this);
                break;
            case 'callflows': $api = new Api\Callflows($this);
                break;
            case "carrier_resources": $api = new Api\CarrierResources($this);
                break;
            case "cdrs": $api = new Api\Cdrs($this);
                break;
            case "click_to_calls": $api = new Api\ClickToCalls($this);
                break;
            case "conferences": $api = new Api\Conferences($this);
                break;
            case "devices": $api = new Api\Devices($this);
                break;
            case "directories": $api = new Api\Directories($this);
                break;
            case "faxes": $api = new Api\Faxes($this);
                break;
            case "groups": $api = new Api\Groups($this);
                break;
            case "menus": $api = new Api\Menus($this);
                break;
            case "phone_numbers": $api = new Api\PhoneNumbers($this);
                break;
            case "queues": $api = new Api\Queues($this);
                break;
            case "registrations": $api = new Api\Registrations($this);
                break;
            case "time_of_day_routes": $api = new Api\TimeOfDayRoutes($this);
                break;
            case "users": $api = new Api\Users($this);
                break;
            case "voicemail_boxes": $api = new Api\VoicemailBoxes($this);
                break;
            case "webhooks": $api = new Api\Webhooks($this);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name));
        }

        return $api;
    }

    /**
     * 
     * @throws AuthenticationException
     */
    private function setupClientState() {

        $payload = new stdClass();
        $payload->data = new stdClass();
        $payload->data->credentials = md5($this->username . ":" . $this->password);
        $payload->data->realm = $this->siprealm;
        $client = new GuzzleClient($this->options['base_url']);
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
    
    private function requestBaseAccount(){
        $this->baseAccount = $this->api('accounts')->get($this->getClientState()->account_id);
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

}
