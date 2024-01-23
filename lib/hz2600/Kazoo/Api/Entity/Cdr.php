<?php
namespace Kazoo\Api\Entity;

use \Kazoo\Common\Exception\ReadOnlyException;
use \Kazoo\Common\ChainableInterface;

class Cdr extends AbstractEntity
{
    /**
     *
     *
     */
    public function __construct(ChainableInterface $chain, array $arguments = array()) {
        parent::__construct($chain, $arguments);
        $this->readOnly();
    }
}
