<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class Fax extends AbstractEntity {

    protected static $_schema_name = "faxes.json";
    protected static $_callflow_module = "fax";

    public function initDefaultValues() {
        
    }

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        return $this->_default_callflow_data;
    }

    /**
     * 
     * @param string $name
     * @param null|array $arguments
     * @return \Kazoo\Api\Data\AbstractEntity
     */
    public function __call($name, $arguments) {
        switch (strtolower($name)) {
            case 'save':
                $result = $this->_client->put($this->_uri . "/outgoing", $this->getData());
                $this->updateFromResult($result->data);
                break;
        }

        return $this;
    }

}