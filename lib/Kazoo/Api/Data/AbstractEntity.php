<?php

namespace Kazoo\Api\Data;

use Kazoo\Api\JsonSchemaObjectFactory;
use Kazoo\Exception\RuntimeException;
use stdClass;

/**
 * Entity abstraction
 */
abstract class AbstractEntity {

    protected $_client;
    protected $_uri;
    protected $_data;
    protected $_state = NULL;
    protected $_default_callflow_data;
    protected $_single = FALSE;

    /**
     *
     * @var null|string
     */
    protected $_schema_json;

    const STATE_NEW = 'EMPTY';
    const STATE_PARTIAL_HYDRATED = 'PARTIAL';
    const STATE_HYDRATED = 'HYDRATED';
    const DOC_KEY = 'id';

    /**
     * 
     * @param \Kazoo\Client $client
     * @param string $uri
     */
    public function __construct(\Kazoo\Client $client, $uri, $data = null) {
        $this->_client = $client;
        $this->_uri = $uri;
        $this->_data = new stdClass();
        $this->_schema_json = $this->getSchemaJson();
        $this->_default_callflow_data = new stdClass();

        JsonSchemaObjectFactory::hydrateNew($this);
        $this->initDefaultValues();
        
        if (is_null($data)) {
            $this->changeState(self::STATE_NEW);
        } else {
            $this->updateFromResult($data);
        }
    }   
    
    abstract protected function initDefaultValues();
        
    public function getUri(){
        return $this->_uri;
    }
        
    public function getCallflowModuleName() {
        return static::$_callflow_module;
    }
    
    protected function getCallflowDefaultData(){
        return $this->_default_callflow_data;
    }

    public function getSchemaJson() {
        
        if(is_null(static::$_schema_name)) {
            $this->_schema_json = null;
        } else {
            $this->_schema_json = file_get_contents($this->_client->getOption('schema_dir') . "/" . static::$_schema_name);
        }
        
        return $this->_schema_json;
    }

    /**
     * 
     * @param stdClass $data
     */
    private function setData(stdClass $data) {
        $this->_data = (object) array_replace_recursive((array) $this->_data, (array) $data);
    }

    public function single_entity() {
        $this->_single = TRUE;
        $this->_state = self::STATE_HYDRATED;

    }

    /**
     * 
     * @param type $state
     */
    private function changeState($state) {
        $this->_state = $state;
    }

    /**
     * 
     * @param stdClass $result
     * @return \Kazoo\Api\Data\AbstractEntity
     */
    public function updateFromResult(stdClass $result) {
        $this->setData($result);
        $this->changeState(self::STATE_HYDRATED);
        return $this;
    }

    /**
     * 
     * @param stdClass $result
     * @return \Kazoo\Api\Data\AbstractEntity
     */
    public function partialUpdateFromResult(stdClass $result) {
        $this->setData($result);
        $this->changeState(self::STATE_PARTIAL_HYDRATED);
        return $this;
    }

//    public fucntion getCallflowModule(){
//        
//    }

    /**
     * 
     * @param type $prop
     * @return type
     */
    public function __get($prop) {
        $return = null;
        switch ($this->_state) {
            case self::STATE_NEW:
            case self::STATE_PARTIAL_HYDRATED:
                $pk = self::DOC_KEY;
                if (isset($this->$pk)) {
                    $result = $this->_client->get($this->_uri, array());
                    $this->updateFromResult($result->data);
                    $return = $this->_data->$prop;
                } else {
                    if (property_exists($this->_data, $prop)) {
                        $return = $this->_data->$prop;
                    }
                }
                break;
            case self::STATE_HYDRATED:
                if (property_exists($this->_data, $prop)) {
                    $return = $this->_data->$prop;
                }
                break;
        }
        
        return $return;
    }

    public function __isset($key) {
        return isset($this->_data->$key);
    }

    public function __unset($key) {
        unset($this->_data->$key);
    }

    /**
     * 
     * @param type $prop
     * @param type $value
     */
    public function __set($prop, $value) {
        $this->_data->$prop = $value;
    }

    /**
     * 
     * @return type
     */
    public function __toString() {
        return $this->toJSON();
    }

    /**
     * 
     * @return type
     */
    public function getData() {
        return $this->_data;
    }

    /**
     * 
     * @return json
     */
    public function toJSON() {
        return json_encode($this->getData(), false);
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
                if(strlen($this->id) > 0 || $this->_single){
                    $result = $this->_client->post($this->_uri, $this->getData());
                } else {
                    $result = $this->_client->put($this->_uri, $this->getData());
                }
                $this->updateFromResult($result->data);
                break;
            case 'delete':
                return $this->_client->delete($this->_uri);
                break;
        }

        return $this;
    }

}
