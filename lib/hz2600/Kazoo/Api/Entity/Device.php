<?php

namespace Kazoo\Api\Entity;

class Device extends AbstractEntity
{
    public function quickcall($number, $auto_answer = true) {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        $this->setTokenValue('quickcall_number', $number);
        $this->setTokenValue('auto_answer', $auto_answer ? 'true' : 'false');
        $this->get(array(), '/quickcall/{quickcall_number}?auto_answer={auto_answer}');
    }
}
