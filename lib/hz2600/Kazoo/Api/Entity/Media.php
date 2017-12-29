<?php

namespace Kazoo\Api\Entity;

class Media extends AbstractEntity
{
    /**
     * posts media file
     * @param  string $path      Local path of media file
     * @param  string $mime_type file mime-type
     */
    public function postRaw($path, $mime_type)
    {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        $bin = file_get_contents($path);
        $b64 = 'data:'.$mime_type.';base64,'.base64_encode($bin);
        $uri = $this->getURI('/raw');
        $x   = $this->getSDK()->post($uri, $b64, array('Content-Type' => 'application/x-base64'));
    }


    protected function getCollectionName(){
        return "media";
    }

}
