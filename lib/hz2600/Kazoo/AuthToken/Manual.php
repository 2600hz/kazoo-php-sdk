<?php

namespace Kazoo\AuthToken;

use \stdClass;

use \Kazoo\SDK;

/**
 *
 */
class Manual implements AuthTokenInterface
{

    /**
     *
     * @var string auth_token
     */
    private static $auth_token;

    /**
     *
     * @var string account_id
     */
    private static $account_id;

    /**
     *
     * @var SDK
     */
    private $sdk;

    /**
     * __construct
     *
     * @param string $auth_token
     * @param string $account_id
     * @return AuthToken\Manual
     */
    public function __construct($auth_token, $account_id = null){
        @session_start();
        $this->setToken($auth_token);
        $this->setAccountId($account_id);
        $_SESSION['Kazoo']['AuthToken']['Manual'] = $auth_token;
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
    public function setAccountId($account_id) {
        $this->account_id = $account_id;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getAccountId() {
        return $this->account_id;
    }

    /**
     * setToken
     *
     * @param mixed $token
     * @return AuthToken\Manual
     */
    public function setToken($token){
        $this->auth_token = $token;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getToken() {
        return $this->auth_token;
    }

    /**
     *
     *
     */
    public function reset() {
        if (isset($_SESSION['Kazoo']['AuthToken']['Manual'])) {
            unset($_SESSION['Kazoo']['AuthToken']['Manual']);
        }
    }
}
