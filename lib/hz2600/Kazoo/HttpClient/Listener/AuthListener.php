<?php

namespace Kazoo\HttpClient\Listener;

use \Kazoo\SDK;

use \GuzzleHttp\Event\BeforeEvent;

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
     * @param \Guzzle\Event\BeforeEvent $event
     */
    public function onRequestBeforeSend(BeforeEvent $event) {
        $token = $this->getSDK()->getAuthToken()->getToken();
        $event->getRequest()->addHeader('X-Auth-Token', $token);
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
