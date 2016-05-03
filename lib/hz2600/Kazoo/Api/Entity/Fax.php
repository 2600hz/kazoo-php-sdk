<?php

namespace Kazoo\Api\Entity;

class Fax extends AbstractEntity
{
    protected function getUriSnippet() {
        return "/faxes/outgoing/".$this->getId();
    }
}

