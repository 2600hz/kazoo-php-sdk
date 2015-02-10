<?php

namespace Kazoo\Api\Entity;

class Channel extends AbstractEntity
{
    public function executeCommand($command) {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());

        $data = array("data" => $command);

        $uri = $this->getUri();
        return $this->getSDK()->post($uri
                                     ,json_encode($data)
                                     ,array("content-type", "application/json")
        );
    }
}
