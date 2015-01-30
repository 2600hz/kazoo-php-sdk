<?php

namespace Kazoo\Api\Entity;

class Device extends AbstractEntity
{
    public function quickcall($number) {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        $this->setTokenValue('quickcall_number', $number);
        $this->get(array(), '/quickcall/{quickcall_number}');
    }
}
