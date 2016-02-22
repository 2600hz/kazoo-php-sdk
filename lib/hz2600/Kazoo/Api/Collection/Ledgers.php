<?php

namespace Kazoo\Api\Collection;

class Ledgers extends AbstractCollection {

    public function debit($data = array()) {
        $response = $this->put(json_encode($data), "/debit");
        $this->setCollection($response->getData());
        return $this;
    }

    public function credit($data = array()) {
        $response = $this->put(json_encode($data), "/credit");
        $this->setCollection($response->getData());
        return $this;
    }

}