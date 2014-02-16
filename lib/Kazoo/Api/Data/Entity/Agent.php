<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class Agent extends AbstractEntity {

    protected static $_schema_name = null;
    protected static $_callflow_module = "agent";

    public function initDefaultValues() {
        
    }

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        return $this->_default_callflow_data;
    }

}