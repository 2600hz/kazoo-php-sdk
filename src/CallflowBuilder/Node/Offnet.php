<?php

namespace CallflowBuilder\Node; 

class Resource extends AbstractNode
{
    public function __construct() {
        parent::__construct();
        $this->module = "offnet";
   }   
}
