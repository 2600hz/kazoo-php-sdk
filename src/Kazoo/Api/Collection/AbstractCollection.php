<?php

namespace Kazoo\Api\Collection;

abstract class AbstractCollection extends \Kazoo\Api\AbstractResource {

    public function fetch(array $filter = array()) {
        return $this->get($filter);
    }
}