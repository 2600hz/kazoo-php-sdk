<?php

namespace Kazoo\Api\Data;

use Iterator;
use Countable;

abstract class AbstractCollection implements Iterator, Countable {

    private $position = 0;
    private $array = array();

    public function __construct($list) {
        $this->array = $list;
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
        return count($this->array);
    }

    public function getUriPart(){
        return static::$_uri_part;
    }
    
    private function peekLocal($resource_id){
        return in_array($resource_id, $this);
    }
}