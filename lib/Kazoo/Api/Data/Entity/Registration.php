<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class Registration extends AbstractEntity {

    protected static $_schema_name = null;
    protected static $_callflow_module = null;

    public function initDefaultValues() {
        
    }

    public function getCallflowDefaultData() {
        return $this->_default_callflow_data;
    }

}