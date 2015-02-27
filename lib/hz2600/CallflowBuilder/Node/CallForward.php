<?php

namespace CallflowBuilder\Node; 

class CallForward extends AbstractNode
{
    public function __construct() {
        parent::__construct();
        $this->module = "call_forward";
    }   

    public function action($action){
        $this->data->action = $action;  
    }
}
