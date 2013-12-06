<?php

namespace Kazoo\HttpClient\Listener;

use Guzzle\Common\Event;
use Guzzle\Http\Message\RequestFactory;
use Kazoo\Client;
use Kazoo\Exception\RuntimeException;

class AuthListener {

    private $token;

    public function __construct($token) {
        $this->token = $token;
    }

    public function onRequestBeforeSend(Event $event) {
        $event['request']->setHeader('X-Auth-Token:', sprintf('token %s', $this->token));
    }

}
