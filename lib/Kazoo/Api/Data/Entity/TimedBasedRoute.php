<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class TimedBasedRoute extends AbstractEntity {

    protected static $_schema_name = "temporal_routes.json";
    protected static $_callflow_module = "temporal_route";

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        return $this->_default_callflow_data;
    }

}