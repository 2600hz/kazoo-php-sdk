<?php

namespace Kazoo\Api\Data\Entity;
use Kazoo\Api\Data\AbstractEntity;
use Kazoo\Api\Data\Entity\CallflowNode;

/**
 * Callflow Entity maps to a REST resource.
 * 
 */
class Callflow extends AbstractEntity {
    protected static $_schema_name = "callflows.json";
    protected static $_callflow_module = "callflow";
    
    public function addNumber($number) {
        $numbers = $this->numbers;
        $numbers[] = $number;
        $this->numbers = $numbers;
    }
    
    //Overriding to see if we have feature codes set
    public function getData() {
        $data = $this->_data;
        if(property_exists($data, 'featurecode')) {
            if(strlen($data->featurecode->name) == 0 && strlen($data->featurecode->number) == 0){
                unset($data->featurecode);
            }
        }
        return $data;
    }
    
    public function getNewCallflowNode(){
        return new CallflowNode();
    }
    
    public function setFlow(CallflowNode $root){
        $this->flow = $root->renderFlow();
    }
}