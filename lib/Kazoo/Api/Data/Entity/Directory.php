<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;
use stdClass;

class Directory extends AbstractEntity {

    protected static $_schema_name = "directories.json";
    protected static $_callflow_module = "directory";

    public function initDefaultValues() {
        $this->min_dtmf = '3';
        $this->max_dtmf = '0';
        $this->sort_by = 'last_name';
        $this->confirm_match = false;
    }

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        return $this->_default_callflow_data;
    }

}