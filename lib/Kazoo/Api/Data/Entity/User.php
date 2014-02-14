<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class User extends AbstractEntity {

    protected static $_schema_name = "users.json";
    protected static $_callflow_module = "user";

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        $this->_default_callflow_data->timeout = "20";
        $this->_default_callflow_data->can_call_self = false;
        return $this->_default_callflow_data;
    }

}