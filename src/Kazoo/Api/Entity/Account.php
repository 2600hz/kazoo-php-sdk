<?php

namespace Kazoo\Api\Entity;

use \stdClass;

class Account extends AbstractEntity {
    protected $url = '/accounts/{account_id}';

    public function children(array $filter = array()) {
        return $this->get($filter, '/children');
    }

    public function descendants(array $filter = array()) {
        return $this->get($filter, '/descendants');
    }

    public function siblings(array $filter = array()) {
        return $this->get($filter, '/siblings');
    }

    public function channels(array $filter = array()) {
        return $this->get($filter, '/channels');
    }

    public function apiKey(array $filter = array()) {
        return $this->get($filter, '/api_key');
    }

    public function move($accountId) {
        //TODO: figure out the tmp url thing....
        //$this->url .= '/move';
        $payload = new stdClass();
        //return $this->post($payload);
    }
}