<?php

namespace CallflowBuilder\Node; 

class CallForward extends AbstractNode
{
    public function __construct() {
        parent::__construct();
        $this->module = "hotdesk";
    }   

    public function action($action){
        $this->data->action = $action;  
    }
}
