<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class TimeBasedRoute extends AbstractEntity {

    protected static $_schema_name = "temporal_rules.json";
    protected static $_callflow_module = "temporal_route";

    public function initDefaultValues() {
        $this->time_window_start = "32400";
        $this->time_window_stop = "61200";
        $this->wdays = array("monday", "tuesday", "wensday", "thursday", "friday");
        $this->ordinal = "every";
        $this->days = 
        $this->interval = 1;
        $this->cycle = "weekly";  //options weekly|monthly|yearly
        $this->start_date = strtotime(date('Y-m-d')) + \Kazoo\Client::GREGORIAN_OFFSET;
        
        unset($this->month);    //Remove these properties by default
        unset($this->days);     //Remove these properties by default
        
    }
    
    public function setStartDate($yyyy_mm_dd_string){
        $this->start_date = strtotime($yyyy_mm_dd_string) + \Kazoo\Client::GREGORIAN_OFFSET;
    }
    
    public function getStartDate(){
        return date(\Kazoo\Client::DATE_FORMAT, $this->start_date - \Kazoo\Client::GREGORIAN_OFFSET);
    }

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        return $this->_default_callflow_data;
    }

}