<?php

namespace CallflowBuilder\Node; 

class Resource extends AbstractNode
{
    public function __construct($id) {
        parent::__construct();
        $this->module = "resources";
        $this->data->hunt_account_id = $id;   
   }   
}
