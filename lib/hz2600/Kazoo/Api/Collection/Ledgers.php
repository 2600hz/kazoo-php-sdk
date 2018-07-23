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

    public function per_minute_voip(array $filter = array()) {
        $filter = $this->getFilter($filter);
        $response = $this->get($filter, '/per-minute-voip');
        $this->setCollection($response->getData());
        return $this;
    }

    public function per_minute_voip_csv(array $filter = array()) {
        $filter["accept"] = "csv";
        $filter = $this->getFilter($filter);
        $response = $this->get($filter, '/per-minute-voip');
        return $response;
    }

}
