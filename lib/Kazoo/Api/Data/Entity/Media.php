<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class Media extends AbstractEntity {

    protected static $_schema_name = "media.json";
    protected static $_callflow_module = "media";

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        return $this->_default_callflow_data;
    }

}