<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class PhoneNumber extends AbstractEntity {

    protected static $_schema_name = "phone_numbers.json";
    protected static $_callflow_module = "phone_number";

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        return $this->_default_callflow_data;
    }

}