<?php

namespace Kazoo\Api\Data\Entity;
use Kazoo\Api\Data\AbstractEntity;

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
        if(is_object($data->featurecode)){
            if(strlen($data->featurecode->name) == 0 && strlen($data->featurecode->number) == 0){
                unset($data->featurecode);
            }
        }
        return $data;
    }
    
}