<?php

namespace Kazoo\Api\Data;

use stdClass;

/**
 * Entity abstraction
 */
abstract class AbstractEntity {

    protected $_client;
    protected $_uri;
    protected $_data;
    protected $_state = NULL;
    protected $_callflow_module;
    
    /**
     *
     * @var null|string
     */
    protected $_schema_json;

    const STATE_EMPTY = 'EMPTY';
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
        $this->_schema_json = $this->getSchemaJson();
        
        if(is_null($data)){
            $this->_data = new stdClass();
            $this->changeState(self::STATE_EMPTY);
        } else {
            $this->updateFromResult($data);
        }
    }
    
    public function getSchemaJson() {
        $this->_schema_json = file_get_contents($this->client->getOption('schema_dir') . "/" . static::$_schema_name);
        return $this->_schema_json;
    }

    /**
     * 
     * @param stdClass $data
     */
    private function setData(stdClass $data) {
        $this->_data = (object) array_replace_recursive((array)$this->_data, (array)$data);
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
        $this->_uri = $this->_uri . "/" . $result->id;
        $this->changeState(self::STATE_HYDRATED);
        return $this;
    }
    
    public fucntion getCallflowModule(){
        
    }

    /**
     * 
     * @param type $prop
     * @return type
     */
    public function __get($prop) {
        echo "Prop:\t" . $prop . "\n";
        echo "State:\t" . $this->_state . "\n";
        switch ($this->_state) {
            case self::STATE_EMPTY:
                $pk = self::DOC_KEY;
                if (isset($this->$pk)) {
                    $result = $this->_client->get($this->_uri, array());
                    $this->updateFromResult($result);
                } else {
                    if (property_exists($this->_data, $prop)) {
                        return $this->_data->$prop;
                    }
                }
                break;
            case self::STATE_HYDRATED:
                if (property_exists($this->_data, $prop)) {
                    return $this->_data->$prop;
                }
                break;
        }
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

                break;
            case 'delete':
                break;
        }

        return $this;
    }

}