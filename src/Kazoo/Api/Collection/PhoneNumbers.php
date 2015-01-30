<?php

namespace Kazoo\Api\Collection;

class PhoneNumbers extends AbstractCollection
{
    public function classifiers() {
        $response = $this->get(array(), '/classifiers');
        $this->setCollection($response->getData());
        $element_wrapper = $this->getElementWrapper();
        $element_wrapper->unfetchable();
        return $this;
    }

    public function find() {
    }

    public function locality() {
    }

    public function check() {
    }

    /**
     *
     *
     */
    public function fetch(array $filter = array()) {
        $response = $this->get($this->getFilter($filter));
        $data = $response->getData();
        $this->setCollection($data->numbers);
        $this->rewind();
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
