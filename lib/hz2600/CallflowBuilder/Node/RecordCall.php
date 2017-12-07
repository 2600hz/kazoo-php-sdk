<?php

namespace CallflowBuilder\Node;

class RecordCall extends AbstractNode
{
    public function __construct() {
        parent::__construct();
        $this->module = 'record_call';
    }

    public function action($action){
        $this->data->action = $action;  
        return $this;
    }

    public function url($url){
        $this->data->url = $url;  
        return $this;
    }

    public function format($format){
        $this->data->format = $format;  
        return $this;
    }

    public function time_limit($time_limit){
        $this->data->time_limit = $time_limit;  
        return $this;
    }

    public function record_on_answer($record_on_answer){
        $this->data->record_on_answer = $record_on_answer;  
        return $this;
    }

    public function record_on_bridge($record_on_bridge){
        $this->data->record_on_bridge = $record_on_bridge;  
        return $this;
    }
}
