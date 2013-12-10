<?php

namespace Kazoo\HttpClient\Listener;

use Guzzle\Common\Event;

class AuthListener {

    private $token;

    public function __construct($token) {
        $this->token = $token;
    }

    public function onRequestBeforeSend(Event $event) {
        $event['request']->setHeader('X-Auth-Token', $this->token);
    }

}
