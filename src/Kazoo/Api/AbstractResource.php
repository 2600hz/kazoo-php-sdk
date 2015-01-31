<?php

namespace Kazoo\Api;

use \Kazoo\Common\ChainableInterface;

abstract class AbstractResource implements ChainableInterface
{
    /**
     *
     *
     */
    private $chain;

    /**
     *
     *
     */
    private $arguments;

    /**
     *
     *
     */
    private $token_values = array();

    /* CHAINABLE INTERFACE */
    /**
     *
     *
     */
    public function getTokenUri() {
        return $this->getChain()->getTokenUri() . $this->getUriSnippet();
    }

    /**
     *
     *
     */
    public function getSDK() {
        return $this->getChain()->getSDK();
    }

    /**
     *
     *
     */
    public function getTokenValues() {
        return array_merge($this->getChain()->getTokenValues(), $this->token_values);
    }
    /* END OF CHAINABLE INTERFACE */

    /**
     *
     *
     */
    public function __construct(ChainableInterface $chain, array $arguments = array()) {
        $this->setChain($chain);
        $this->setArguments($arguments);
    }

    /**
     *
     *
     */
    public function getUri($append_uri = null) {
        $token_uri = $this->getTokenUri();
        if (!is_null($append_uri)) {
            $token_uri .= $append_uri;
        }

        $token_values = $this->getTokenValues();
        return $this->getSDK()->getTokenizedUri($token_uri, $token_values);
    }

    /**
     *
     *
     */
    protected function getChain() {
        return $this->chain;
    }

    /**
     *
     *
     */
    protected function setChain(ChainableInterface $chain) {
        $this->chain = $chain;
    }

    /**
     *
     *
     */
    protected function getArguments() {
        return $this->arguments;
    }

    /**
     *
     *
     */
    protected function setArguments($arguments) {
        return $this->arguments = $arguments;
    }

    /**
     *
     *
     */
    protected function setTokenValue($token, $value) {
        if (is_null($value)) {
            unset($this->token_values[$token]);
        } else {
            $this->token_values[$token] = $value;
        }
    }

    /**
     *
     *
     */
    protected function get(array $filter = array(), $append_uri = null) {
        $uri = $this->getUri($append_uri);
        return $this->getSDK()->get($uri, $filter);
    }

    /**
     *
     *
     */
    protected function put($payload, $append_uri = null) {
        $uri = $this->getUri($append_uri);
        return $this->getSDK()->put($uri, $payload);
    }

    /**
     *
     *
     */
    protected function post($payload, $append_uri = null) {
        $uri = $this->getUri($append_uri);
        return $this->getSDK()->post($uri, $payload);
    }

    /**
     *
     *
     */
    protected function delete($payload = null, $append_uri = null) {
        $uri = $this->getUri($append_uri);
        return $this->getSDK()->delete($uri, $payload);
    }
}
