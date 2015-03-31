<?php

namespace Kazoo\Api\Entity;

class Resource extends AbstractEntity
{
    public function globalsave($append_uri = null) {
        $id = $this->getId();
        $payload = $this->getPayload();
        $this->setTokenValue($this->getEntityIdName(), $id);

        if (empty($id)) {
            $response = $this->globalput($payload, $append_uri);
        } else {
            $response = $this->globalpost($payload, $append_uri);
        }

        $entity = $response->getData();
        $this->setEntity($entity);    
        return $this;
    }


    public function globalremove() {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        $this->globaldelete();
        $this->reset();
        return $this;
    }

    protected function globalput($payload, $append_uri = null) {
        $uri = $this->getUri($append_uri);
        $pos1 = strpos($uri,'v1')+2;
        $pos2 = strpos($uri,'/resources'); 
        $uri = substr_replace($uri,"", $pos1,$pos2-$pos1);
        return $this->getSDK()->put($uri, $payload);
    }

    protected function globalpost($payload, $append_uri = null) {
        $uri = $this->getUri($append_uri);
        $pos1 = strpos($uri,'v1')+2;
        $pos2 = strpos($uri,'/resources'); 
        $uri = substr_replace($uri,"", $pos1,$pos2-$pos1);
        return $this->getSDK()->post($uri, $payload);
    }

    protected function globaldelete($payload = null, $append_uri = null) {
        $uri = $this->getUri($append_uri);
        $pos1 = strpos($uri,'v1')+2;
        $pos2 = strpos($uri,'/resources'); 
        $uri = substr_replace($uri,"", $pos1,$pos2-$pos1);        
        return $this->getSDK()->delete($uri, $payload);
    }
    
}
