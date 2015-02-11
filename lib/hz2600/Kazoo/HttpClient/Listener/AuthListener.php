<?php

namespace Kazoo\HttpClient\Listener;

use \Kazoo\SDK;

use \Guzzle\Common\Event;

/**
 *
 */
class AuthListener
{
    /**
     *
     * @var \Kazoo\SDK
     */
    private $sdk;

    /**
     *
     * @param \Kazoo\SDK $sdk
     */
    public function __construct(SDK $sdk) {
        $this->setSDK($sdk);
    }

    /**
     *
     * @param \Guzzle\Common\Event $event
     */
    public function onRequestBeforeSend(Event $event) {
        $token = $this->getSDK()->getAuthToken()->getToken();
        $event['request']->setHeader('X-Auth-Token', $token);
    }

    /**
     *
     * @return \Kazoo\SDK
     */
    private function getSDK() {
        return $this->sdk;
    }

    /**
     *
     * @param \Kazoo\SDK $sdk
     */
    private function setSDK(SDK $sdk) {
        $this->sdk = $sdk;
    }
}