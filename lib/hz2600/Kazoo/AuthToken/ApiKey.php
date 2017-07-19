<?php

namespace Kazoo\AuthToken;

use \stdClass;

use \Kazoo\SDK;

/**
 *
 */
class ApiKey implements AuthTokenInterface
{
    /**
     *
     * @var SDK
     */
    private $sdk;

    /**
     *
     * @var string
     */
    private $api_key;

    /**
     *
     * @var null|stdClass
     */
    private $auth_response = null;

    /**
     *
     * @var boolean
     */
    private $disabled = false;

    /**
     *
     * @param string $username
     * @param string $password
     * @param string $sipRealm
     */
    public function __construct($api_key) {
        @session_start();
        $this->api_key = $api_key;
    }

    /**
     *
     *
     */
    public function __destruct() {
        if (!is_null($this->auth_response)) {
            $_SESSION['Kazoo']['AuthToken']['ApiKey'] = $this->auth_response;
        }
    }

    /**
     *
     * @return null|SDK
     */
    public function getSDK() {
        return $this->sdk;
    }

    /**
     *
     * @param SDK
     */
    public function setSDK(SDK $sdk) {
        $this->sdk = $sdk;
    }

    /**
     *
     * @return string
     */
    public function getAccountId() {
        $response = $this->getAuthResponse();
        if (isset($response->account_id)) {
            return $response->account_id;
        }
        return "";
    }

    /**
     *
     * @return string
     */
    public function getToken() {
        $response = $this->getAuthResponse();
        if (isset($response->auth_token)) {
            return $response->auth_token;
        }
        return "";
    }

    /**
     *
     *
     */
    public function reset() {
        $this->auth_response = null;
        if (isset($_SESSION['Kazoo']['AuthToken']['ApiKey'])) {
            unset($_SESSION['Kazoo']['AuthToken']['ApiKey']);
        }
    }

    /**
     *
     * @return string
     */
    private function getAuthResponse() {
        if (is_null($this->auth_response)) {
            $this->checkSessionResponse();
        }

        return $this->auth_response;
    }

    /**
     *
     *
     */
    private function checkSessionResponse() {
        if (isset($_SESSION['Kazoo']['AuthToken']['ApiKey'])) {
            $this->auth_response = $_SESSION['Kazoo']['AuthToken']['ApiKey'];
        } else {
            $this->requestToken();
        }
    }

    /**
     *
     *
     */
    private function requestToken() {
        if ($this->disabled) {
            return new stdClass();
        }

        $payload = new stdClass();
        $payload->data = new stdClass();
        $payload->data->api_key = $this->api_key;

        $sdk = $this->getSDK();
        $tokenizedUri = $sdk->getTokenizedUri($sdk->getTokenUri() . "/api_auth");

        $this->disabled = true;
        $response = $sdk->getHttpClient()->put($tokenizedUri, json_encode($payload));
        $this->disabled = false;

        $this->auth_response = $response->getData();
        $this->auth_response->auth_token = $response->getAuthToken();
    }
}
