<?php

namespace CallflowBuilder\Node; 

class Privacy extends AbstractNode
{
    public function __construct() {
        parent::__construct();
        $this->module = "privacy";
    }   

    public function action($action){
        $this->data->action = $action;  
    }
}
