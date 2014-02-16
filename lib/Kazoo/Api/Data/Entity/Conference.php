<?php

namespace Kazoo\Api\Resources;

use Kazoo\Api\AbstractResource;

class Conference extends AbstractResource {

    protected static $_schema_name = "conferences.json";
    protected static $_callflow_module = "conference";

    public function initDefaultValues() {
        
    }

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        return $this->_default_callflow_data;
    }

}