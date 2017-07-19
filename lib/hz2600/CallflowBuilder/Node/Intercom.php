<?php

namespace CallflowBuilder\Node; 

class Intercom extends AbstractNode
{
    public function __construct() {
        parent::__construct();
        $this->module = "intercom";
    }   

    public function action($action){
        $this->data->action = $action;  
    }
}
