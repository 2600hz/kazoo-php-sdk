<?php

namespace Kazoo\Api\Entity;

class Callflow extends AbstractEntity
{
    public function fromBuilder($builder){
        $this->setEntity($builder); 
    } 
    
}
