<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class PhoneNumber extends AbstractEntity {

    protected static $_schema_name = null;
    protected static $_callflow_module = "phone_number";

    public function initDefaultValues() {

    }

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        return $this->_default_callflow_data;
    }

    public function __call($name, $arguments) {
        if (count($arguments) == 1) {
            $uri = $this->_uri . '/' . urlencode($arguments[0]);
        } else {
            $uri = $this->getUri();
        }

        switch (strtolower($name)) {
            case 'save':
                if($this->_state != self::STATE_NEW) {
                    $result = $this->_client->post($uri, $this->getData());
                } else {
                    if (count($arguments) < 1 && !empty($this->id)) {
                        $uri = $this->_uri . '/' . urlencode($this->id);
                    }
                    $result = $this->_client->put($uri, $this->getData());
                }
                if (isset($result->data)) $this->updateFromResult($result->data);
                break;
            case 'activate':
                $result = $this->_client->put($uri . '/activate', $this->getData());
                break;
            case 'port':
                $result = $this->_client->put($uri . '/port', $this->getData());
                break;
            case 'delete':
                return $this->_client->delete($uri);
                break;
        }

        return $this;
    }

}