<?php

namespace Kazoo\Api\Collection;

use \stdClass;

class PhoneNumbers extends AbstractCollection
{
    /**
     *
     *
     */
    public function classifiers() {
        $response = $this->get(array(), '/classifiers');
        $this->setCollection($response->getData());

        $element_wrapper = $this->getElementWrapper();
        $element_wrapper->unfetchable();

        return $this;
    }

    /**
     *
     *
     */
    public function find($prefix, $quantity = 1) {
        $sdk = $this->getSDK();
        $this->setChain($sdk);

        $filters = array(
            'prefix' => $prefix,
            'quantity' => (int)$quantity
        );
        $response = $this->get($filters);
        $this->setCollection($response->getData());

        return $this;
    }

    /**
     *
     *
     */
    public function locality(array $numbers, $quality = "standard") {
        $payload = new stdClass;
        $payload->data = new stdClass;
        $payload->data->numbers = $numbers;
        $payload->data->quality = $quality;

        $response = $this->post(json_encode($payload), '/locality');
        $this->setCollection($response->getData());

        $element_wrapper = $this->getElementWrapper();
        $element_wrapper->unfetchable();

        return $this;
    }

    /**
     *
     *
     */
    public function check() {
        $payload = new stdClass;
        $payload->data = new stdClass;
        $payload->data->numbers = $numbers;
        $payload->data->quality = $quality;

        $response = $this->post(json_encode($payload), '/check');
        $this->setCollection($response->getData());

        $element_wrapper = $this->getElementWrapper();
        $element_wrapper->unfetchable();

        return $this;
    }

    /**
     *
     *
     */
    public function fetch(array $filter = array()) {
        $response = $this->get($this->getFilter($filter));
        $data = $response->getData();
        $this->setCollection($data->numbers);

        return $this;
    }

    /**
     *
     *
     */
    protected function loadElementWrapper($element, $key) {
        $element_wrapper = $this->getElementWrapper();
        $element_wrapper->setElement($element, $key);
        return $element_wrapper;
    }
}
