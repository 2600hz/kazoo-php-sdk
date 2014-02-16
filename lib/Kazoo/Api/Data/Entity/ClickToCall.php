<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class ClickToCall extends AbstractEntity {

    protected static $_schema_name = "clicktocall.json";
    protected static $_callflow_module = "clicktocall";

    public function initDefaultValues() {
        
    }

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        return $this->_default_callflow_data;
    }

}