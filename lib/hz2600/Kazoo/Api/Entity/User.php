<?php

namespace Kazoo\Api\Entity;

use \Kazoo\Api\Collection\Devices;

class User extends AbstractEntity
{
    public function devices(array $filter = array()) {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());

        $collection_name = '\\Kazoo\\Api\\Collection\\Devices';
        return new $collection_name($this, array($filter));
    }

    public function quickcall($number, $options = array()) {
        $url = '/quickcall/{quickcall_number}';
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        $this->setTokenValue('quickcall_number', $number);
        $this->get($options, $url);
    }
}
