<?php

namespace CallflowBuilder\Node; 

use \stdClass; 

abstract class AbstractNode 
{
    protected $children = array();
    protected $module;
    protected $data;

    protected function getLast() {
        if (!$this->children) {
            return $this;
        }   
        return $this->children['_']->getLast();
    }   

    public function __construct() {
        $this->data = new stdClass();
        $this->module = ""; 
    }   

    public function addChildren($nodes) {
         if (!is_array($nodes)) {
             $nodes = array($nodes);
         }   
         foreach($nodes as $node) {
             $end = $this->getLast();
             $end->children['_'] = $node;
         }   
         return $this->getLast();
    }

    public function addLastChild(AbstractNode $child, $index = '_'){
        $this->getLast()->children[$index] = $child;
        return $child; 
    }
   
    public function addChild(AbstractNode $child, $index = '_') {
        $this->children[$index] = $child;
        return $child;
    }   
    
    public function removeChildren($index = '_') {
        unset($this->children[$index]);
        return $this;
    }   

    public function removeChild($index = '_') {
        $grandchildren = $this->children[$index]->children[$index];
        unset($this->children[$index]);
        $this->addChild($grandchildren); 
        return $this;
    }   

    public function build() {
        $flow = new stdClass();
        $flow->data = $this->data;
        $flow->module = $this->module;
        $flow->children = new stdClass();         

        foreach ($this->children as $index => $child) {
            $flow->children->$index = $child->build();
        }   
 
        return $flow;
    }   
   
    public function __toString() {
        return json_encode($this->build());
    }   
}
