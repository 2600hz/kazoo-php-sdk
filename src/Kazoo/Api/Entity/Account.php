<?php

namespace Kazoo\Api\Entity;

use \stdClass;

use \Kazoo\Common\ChainableInterface;

class Account extends AbstractEntity
{
    /**
     *
     *
     */
    public function __construct(ChainableInterface $chain, array $arguments = array()) {
        if (!array_key_exists(0, $arguments)) {
            $arguments[0] = $chain->getSDK()->getAuthToken()->getAccountId();
        }
        parent::__construct($chain, $arguments);
    }
}
