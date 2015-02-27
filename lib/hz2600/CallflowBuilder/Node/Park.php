<?php

namespace CallflowBuilder\Node; 

class Park extends AbstractNode
{
    public function __construct() {
        parent::__construct();
        $this->module = "park";
    }   

    public function action($action){
        $this->data->action = $action;  
    }
}
