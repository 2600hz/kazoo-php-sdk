<?php

namespace CallflowBuilder\Node; 

use \stdClass; 

class PageGroup extends AbstractNode
{
    public function __construct($name) {
        parent::__construct();
        $this->module = "page_group";
        $this->name($name); 
    }   
    
    public function name($name){
        $this->data->name = $name;
        return $this; 
    }
    
    public function endpoints(array $endpoints){
        $this->data->endpoints = array(); 
        
        foreach ($endpoints as $id => $type){
            $endpoint = new stdClass(); 
            $endpoint->type    = $type;
            $endpoint->id      = $id;
            array_push($this->data->endpoints, $endpoint); 
        }
        return $this; 
    }
}
