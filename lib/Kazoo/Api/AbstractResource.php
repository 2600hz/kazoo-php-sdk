<?php

namespace Kazoo\Api;

abstract class AbstractResource {
    
    public function toJSON(){
        return json_encode($this, false);
    }

}