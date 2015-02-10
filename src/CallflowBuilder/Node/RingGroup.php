<?php

namespace CallflowBuilder\Node; 

class RingGroup extends CallflowNodes
{
    public function __construct($name) {
        parent::__construct();
        $this->module = "ring_group";
        $this->name($name); 
        $this->setTimeout();
        $this->setStrategy(); 
    }   
    
    public function setName($name){
        $this->data->name = $name;
        return $this; 
    }

    public function setStrategy($value = "simultanious"){
         $this->data->strategy = $value;
         return $this;
    }   

    public function setTimeout($timeout = 20){
        $this->data->timeout = $timeout; 
        return $this; 
    }  
    
    public function addEndpoint($endpoints){
        if(!is_array($endpoints){
            $endpoints = array($endpoints); 
        }
  
        foreach ($endpoints as $endpoint){
            array_push($this->data->endpoints, $endpoint); 
        }
    }
}
