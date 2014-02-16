<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;
use stdClass;

class RingGroup extends AbstractEntity {

    protected static $_schema_name = null;
    protected static $_callflow_module = "ring_group";

    public function initDefaultValues() {
        
    }

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        $this->_default_callflow_data->endpoints = $this->endpoints;
        return $this->_default_callflow_data;
    }

    public function addUserToGroup($user_id) {

        if ($this->endpoints instanceof stdClass) {
            $endpoints = $this->endpoints;
        } else {
            $endpoints = new stdClass();
        }

        $endpoints->$user_id = new stdClass();
        $endpoints->$user_id->type = "user";

        $this->endpoints = $endpoints;
    }

    public function addDeviceToGroup($device_id) {

        if ($this->endpoints instanceof stdClass) {
            $endpoints = $this->endpoints;
        } else {
            $endpoints = new stdClass();
        }

        $endpoints->$device_id = new stdClass();
        $endpoints->$device_id->type = "device";

        $this->endpoints = $endpoints;
    }

}