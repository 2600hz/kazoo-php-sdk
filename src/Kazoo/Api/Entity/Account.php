<?php

namespace Kazoo\Api\Entity;

use \stdClass;

class Account extends AbstractEntity {
    protected $url = '/accounts/{account_id}';

    public function children() {
        return $this->get('/children');
    }

    public function descendants() {
        return $this->get('/descendants');
    }

    public function siblings() {
        return $this->get('/siblings');
    }

    public function channels() {
        return $this->get('/channels');
    }

    public function apiKey() {
        return $this->get('/api_key');
    }

    public function move($accountId) {
        //TODO: figure out the tmp url thing....
        //$this->url .= '/move';
        $payload = new stdClass();
        //return $this->post($payload);
    }
}