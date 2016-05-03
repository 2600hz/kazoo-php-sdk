<?php

namespace Kazoo\Api\Entity;

class ResourceTemplate extends AbstractEntity
{
    protected function getUriSnippet() {
        return "/resource/resource_templates/".$this->getId();
    }

}
