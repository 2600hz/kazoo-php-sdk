<?php

namespace Kazoo\AuthToken;

use \stdClass;

use \Kazoo\SDK;

/**
 *
 */
class Manual implements AuthTokenInterface
{

    private static $auth_token;
    private static $account_id;

    public function __construct($auth_token, $account_id = null){
        $this->auth_token = $auth_token;
        $this->account_id = $account_id;
    }

    /**
     *
     * @var SDK
     */
    private $sdk;

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
        return $this->account_id;
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

    }
}
