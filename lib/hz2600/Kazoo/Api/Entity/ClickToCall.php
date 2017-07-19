<?php
namespace Kazoo\Api\Entity;

use \Kazoo\Common\Exception\ReadOnly;
use \Kazoo\Common\ChainableInterface;

class ClickToCall extends AbstractEntity
{
    protected function getEntityIdName() {
        return 'clicktocall';
    }

    protected function getCollectionName() {
        return 'clicktocall';
    }
}
