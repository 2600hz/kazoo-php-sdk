<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;
use stdClass;

class User extends AbstractEntity {

    protected static $_schema_name = "users.json";
    protected static $_callflow_module = "user";

    public function initDefaultValues() {
        
    }

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        $this->_default_callflow_data->timeout = "20";
        $this->_default_callflow_data->can_call_self = false;
        return $this->_default_callflow_data;
    }

    public function addDirectoryEntry($directory_id, $callflow_id) {

        if ($this->directories instanceof stdClass) {
            $directories = $this->directories;
        } else {
            $directories = new stdClass();
        }

        $directories->$directory_id = $callflow_id;
        $this->directories = $directories;
    }

    public function removeDirectoryEntry($directory_id) {

        if ($this->directories instanceof stdClass) {
            $directories = $this->directories;
            unset($directories->$directory_id);
            $this->directories = $directories;
        }
    }

}