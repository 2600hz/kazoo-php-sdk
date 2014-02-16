<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class VoicemailBox extends AbstractEntity {

    protected static $_schema_name = "vmboxes.json";
    protected static $_callflow_module = "voicemail";

    public function initDefaultValues() {
        
    }

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        $this->_default_callflow_data->max_message_length = 500;
        $this->_default_callflow_data->interdigit_timeout = 2000;
        return $this->_default_callflow_data;
    }

}