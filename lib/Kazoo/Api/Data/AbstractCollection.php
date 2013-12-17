<?php

namespace Kazoo\Api;

use Iterator;
use Countable;
use Kazoo\Api\Resource;

abstract class Collection extends Resource implements Iterator, Countable {

    private $position = 0;
    private $array = array();

    public function __construct($client, $uri) {

        $name = get_class($this);

        if (!isset($this->rest_resource_class)) {
            $this->rest_resource_class = "Kazoo\\Api\\Resource\\" . rtrim($name, 's');
        }

        parent::__construct($client, $uri);
    }

    public function getSchemaJson() {
        return file_get_contents($this->client->getOption('schema_dir') . "/" . static::$_schema_name);
    }

    public function toJSON() {
        $json_list = array();
        foreach ($this->array as $nDx => $instance) {
            $json_list[] = $instance->getData();
        }

        return "[" . implode(", ", $json_list) . "]";
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->array[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->array[$this->position]);
    }

    public function count() {
        return 0;
    }

    public function getUriPart(){
        return static::$_uri_part;
    }
    
    private function peekLocal($resource_id){
        return in_array($resource_id, $this);
    }
    
    public function __call($name, $arguments) {
        $request_type = null;
        
        if(is_int($arguments[0])){
            $request_type = ""
        }
        
        switch (strtolower($name)) {
            case 'new':
                return JsonSchemaObjectFactory::getNew($this->client, $this->uri, static::$_resource_class, $this->getSchemaJson());
                break;
            case 'retrieve':
                
                if($this->peekLocal($))
                break;
        }
    }

}