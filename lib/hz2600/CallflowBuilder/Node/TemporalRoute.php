<?php

namespace CallflowBuilder\Node; 


class TemporalRoute extends AbstractNode
{
    public function __construct($timezone = null) {
        parent::__construct();
        $this->module = "temporal_route";
        if (isset($timezone)){
            $this->data->timezone = $timezone; 
        }
    }

    public function action($action){
        $this->data->action = $action; 
        return $this;
    }

    public function rules(array $rules){
        $this->data->rules = $rules;
        return $this; 
    }

    public function timezone($timezone){
        $this->data->timezone = $timezone;
        return $this; 
    }    
    
}


