<?php

namespace Kazoo\Api\Entity;

use \stdClass;

class Channel extends AbstractEntity
{
    public function executeCommand($command) {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
         
        $payload = new stdClass; 
        $payload->data = new stdClass;
        $payload->data = $command;


        return $this->post(json_encode($payload));
    }
}
