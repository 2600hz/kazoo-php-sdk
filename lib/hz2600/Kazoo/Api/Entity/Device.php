<?php

namespace Kazoo\Api\Entity;

class Device extends AbstractEntity
{
    public function quickcall($number, $parameters = array()) {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        $this->setTokenValue('quickcall_number', $number);
        $query_string = '';
        if (!empty($parameters)) {
            $query_string = '?' . http_build_query($parameters);
        }
        $this->get(array(), '/quickcall/{quickcall_number}' . $query_string);
    }
}
