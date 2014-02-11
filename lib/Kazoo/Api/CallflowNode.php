<?php

namespace Kazoo\Api;

class CallflowNode {

    private $_flow;

    public function __construct() {
        $this->_flow = new stdClass();
        $this->_flow->data = new stdClass();
        $this->_flow->module = '';
        $this->_flow->children = new stdClass();
    }

    public function setName($name) {
        $this->_name = $name;
    }

    public function setFlowData(stdClass $data){
        $this->_flow->data = $data;
    }
    
    public function setFlowDataProperty($property, $value){
        $this->_flow->data->$property = $value;
    }
    
    public function getName($name) {
        return $this->_name;
    }

    public function addNumber($number) {
        $this->_numbers[] = $number;
    }

    public function getNumbers() {
        return $this->_numbers;
    }

    public function addDefaultCallflowElement(CallflowNode $node) {
        $this->addChildCallflow("_", $node);
    }

    public function addChildCallflow($key, CallflowNode $node) {
        $this->_flow->children->$key = $node;
    }

    public function toGenericObject() {
        
    }

}