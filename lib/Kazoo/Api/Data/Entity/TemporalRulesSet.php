<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class TemporalRulesSet extends AbstractEntity {

    protected static $_schema_name = "temporal_rules_sets.json";
    protected static $_callflow_module = "temporal_rules_sets";

    public function initDefaultValues() {
        
    }

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        return $this->_default_callflow_data;
    }
}
