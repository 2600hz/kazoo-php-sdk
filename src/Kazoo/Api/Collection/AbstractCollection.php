<?php

namespace Kazoo\Api\Collection;

abstract class AbstractCollection extends \Kazoo\Api\AbstractResource {

    public function fetch() {
        return $this->get();
    }
}