<?php
namespace Kazoo\Api\Entity;

use \Kazoo\Common\Exception\ReadOnly;


class Cdr extends AbstractEntity
{
    public function __set($name, $value) {
        throw new ReadOnly("You can not set properties of a CDR entity!");
    }

    public function save() {
        throw new ReadOnly("You can not save a CDR!");
    }
}
