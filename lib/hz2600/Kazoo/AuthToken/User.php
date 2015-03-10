<?php

namespace Kazoo\AuthToken;

use \stdClass;

use \Kazoo\SDK;

/**
 *
 */
class User implements AuthTokenInterface
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
    private $username;

    /**
     *
     * @var string
     */
    private $password;

    /**
     *
     * @var string
     */
    private $sipRealm;

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
    public function __construct($username, $password, $sipRealm) {
        @session_start();
        $this->username = $username;
        $this->password = $password;
        $this->sipRealm = $sipRealm;
    }

    /**
     *
     *
     */
    public function __destruct() {
        if (!is_null($this->auth_response)) {
            $_SESSION['Kazoo']['AuthToken']['User'] = $this->auth_response;
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
    public function getLoggedInUserId() {
        $response = $this->getAuthResponse();
        if (isset($response->owner_id)) {
            return $response->owner_id;
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
        if (isset($_SESSION['Kazoo']['AuthToken']['User'])) {
            unset($_SESSION['Kazoo']['AuthToken']['User']);
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
        if (isset($_SESSION['Kazoo']['AuthToken']['User'])) {
            $this->auth_response = $_SESSION['Kazoo']['AuthToken']['User'];
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
        $payload->data->credentials = md5($this->username . ":" . $this->password);
        $payload->data->realm = $this->sipRealm;

        $sdk = $this->getSDK();
        $tokenizedUri = $sdk->getTokenizedUri($sdk->getTokenUri() . "/user_auth");

        $this->disabled = true;
        $response = $sdk->getHttpClient()->put($tokenizedUri, json_encode($payload));
        $this->disabled = false;

        $this->auth_response = $response->getData();
        $this->auth_response->auth_token = $response->getAuthToken();
    }
}
