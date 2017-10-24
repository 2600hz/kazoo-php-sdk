<?php

namespace Kazoo\Api\Entity;

class Device extends AbstractEntity
{
    public function quickcall($number, $options = array()) {
        $url = '/quickcall/{quickcall_number}';
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        $this->setTokenValue('quickcall_number', $number);

        return $this->get($options, $url);
    }

    public function sync() {
        $url = '/sync';
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        return $this->post(array(), $url);
    }
}
