<?php

namespace Kazoo\Api\Entity;

class Media extends AbstractEntity
{

    /**
     * downloads or streams a media file
     * @param  boolean $stream  Set to true to stream the file
     * @return binary           Media file
     */
    public function getRaw($stream = false)
    {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        $uri = $this->getURI('/raw');
        $x   = $this->getSDK()->get($uri, array(), array('accept'=>'audio/*', 'content_type'=>'audio/*'));

        header('Content-Type: '.$x->getHeader('Content-Type'));
        header('content-length: '.$x->getHeader('content-length'));

        if (!$stream) {
            header('Content-Disposition: '.$x->getHeader('Content-Disposition'));
        }
        echo $x->getBody();
    }


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
