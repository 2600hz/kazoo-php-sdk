<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class Device extends AbstractEntity {

    protected static $_schema_name = "devices.json";
    protected static $_callflow_module = "device";

    public function initDefaultValues() {
        
    }

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        return $this->_default_callflow_data;
    }

}