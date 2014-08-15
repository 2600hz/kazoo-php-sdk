<?php

namespace Kazoo\Api;

use \Kazoo\Common\ChainableInterface;

abstract class AbstractResource implements ChainableInterface {
    private $chain;
    private $arguments;
    private $token_values = array();

    protected $url;

    /* CHAINABLE INTERFACE */
    public function getTokenUri() {
        return $this->chain->getTokenUri() . $this->url;
    }

    public function getSDK() {
        return $this->chain->getSDK();
    }

    public function getTokenValues() {
        return array_merge($this->chain->getTokenValues(), $this->token_values);
    }
    /* END OF CHAINABLE INTERFACE */

    public function __construct(ChainableInterface $chain, array $arguments = array()) {
        $this->chain = $chain;
        $this->arguments = $arguments;
        return $this;
    }

    public function getUri($appendUri = null) {
        $tokenUri = $this->getTokenUri();

        if (!is_null($appendUri)) {
            $tokenUri .= $appendUri;
        }

        $tokenValues = $this->getTokenValues();
        return $this->getSDK()->getTokenizedUri($tokenUri, $tokenValues);
    }

    protected function setTokenValue($token, $value) {
        if (is_null($value)) {
            unset($this->token_values[$token]);
        } else {
            $this->token_values[$token] = $value;
        }
    }

    protected function getArguments() {
        return $this->arguments;
    }

    protected function get(array $filter = array(), $appendUri = null) {
        $uri = $this->getUri($appendUri);
        return $this->getSDK()->get($uri);
    }

    protected function put($payload) {
        $uri = $this->getUri();
        return $this->getSDK()->put($uri, $payload);
    }

    protected function post($payload) {
        $uri = $this->getUri();
        return $this->getSDK()->post($uri, $payload);
    }

    protected function delete($payload = null) {
        $uri = $this->getUri();
        return $this->getSDK()->delete($uri, $payload);
    }
}
