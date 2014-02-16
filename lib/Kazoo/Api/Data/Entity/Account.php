<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

/**
 * Account Entity maps to a REST resource.
 * 
 */
class Account extends AbstractEntity {

    protected static $_schema_name = "accounts.json";
    protected static $_callflow_module = "account";

    public function initDefaultValues() {
        
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
                if (strlen($this->id) > 0) {
                    $curAccountContext = $this->_client->getAccountContext();
                    $this->_client->setAccountContext($this->id);
                    $result = $this->_client->post($this->_uri, $this->getData());
                    $this->_client->setAccountContext($curAccountContext);
                } else {
                    $result = $this->_client->put($this->_uri, $this->getData());
                }
                $this->updateFromResult($result->data);
                break;
            case 'delete':
                $curAccountContext = $this->_client->getAccountContext();
                $this->_client->setAccountContext($this->id);
                $result = $this->_client->delete($this->_uri);
                $this->_client->setAccountContext($curAccountContext);
                return $result;
                break;
        }

        return $this;
    }

}