<?php

namespace CallflowBuilder\Node; 

class Offnet extends AbstractNode
{
    public function __construct() {
        parent::__construct();
        $this->module = "offnet";
   }   
}
