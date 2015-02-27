<?php

namespace CallflowBuilder\Node; 

class Device extends AbstractNode
{
    public function __construct($id) {
        parent::__construct();
        $this->module = "device";
        $this->data->id = $id;
    }   
    
    public function canCallSelf($value = FALSE){
         $this->data->can_call_self = $value;
         return $this;
    }   

    public function timeout($timeout = 20){
        $this->data->timeout = $timeout; 
        return $this; 
    }  
}
