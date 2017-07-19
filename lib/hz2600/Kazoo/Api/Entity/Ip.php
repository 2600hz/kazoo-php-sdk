<?php

namespace Kazoo\Api\Entity;

class Ip extends AbstractEntity
{
    public function allocate() {
        return $this->save();
    }

    public function release() {
        return $this->remove();
    }

}
