<?php

namespace Kazoo\Api\Entity;

use \stdClass;

use \Exception;

use \Kazoo\Common\ChainableInterface;
use \Kazoo\Api\Collection\Accounts;
use \Kazoo\Common\Exception\ReadOnly;

class AppsLink extends AbstractEntity
{

    public function save(){
        throw new ReadOnly("The entity is read-only");
    }

    public function getUriSnippet(){
        return "/apps_link/authorize";
    }
}
