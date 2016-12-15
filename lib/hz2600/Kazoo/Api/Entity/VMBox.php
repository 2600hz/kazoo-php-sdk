<?php

namespace Kazoo\Api\Entity;

use \Kazoo\Common\ChainableInterface;

class VMBox extends AbstractEntity
{
    public function __construct(ChainableInterface $chain, array $arguments = array()) {
        parent::__construct($chain, $arguments);
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
    }
}
