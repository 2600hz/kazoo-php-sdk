<?php

namespace Kazoo\AuthToken;

use \stdClass;

use \Kazoo\SDK;

/**
 *
 */
class None implements AuthTokenInterface
{
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
        return null;
    }

    /**
     *
     * @return string
     */
    public function getToken() {
        return null;
    }

    /**
     *
     *
     */
    public function reset() {

    }
}
