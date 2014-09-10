<?php

namespace Kazoo\Api\Collection;

use \Exception;
use \stdClass;

use \Kazoo\Common\ChainableInterface;

class ElementWrapper
{
    /**
     *
     *
     */
    private $chain;

    /**
     *
     *
     */
    private $entity_name;

    /**
     *
     *
     */
    private $element;

    /**
     *
     *
     */
    public function __construct(ChainableInterface $chain, $name) {
        $this->setChain($chain);
        $this->setEntityName('\\Kazoo\\Api\\Entity\\' . $name);
    }

    /**
     *
     *
     */
    public function __toString() {
        try {
            return $this->toJson();
        } catch (Exception $e) {
            // Because php...
            // Fatal error: Method xxx::__toString() must not throw an exception
            return json_encode(new stdClass);
        }
    }

    /**
     *
     *
     */
    public function __set($name, $value) {
        // TODO: make this a kazoo sdk specific exception
        throw new Exception("collection elements");
    }

    /**
     *
     *
     */
    public function &__get($name) {
        return $this->getElement()->$name;
    }

    /**
     *
     *
     */
    public function fetch() {
        $entity_name = $this->getEntityName();

        if (is_null($entity_name)) {
            throw new Exception("This is a read only API");
        }

        return new $entity_name($this->getChain(), array($this->getElementId()));
    }

    /**
     *
     *
     */
    public function toJson() {
        return (string)json_encode($this->getElement());
    }

    /**
     *
     *
     */
    public function setElement(&$element) {
        if(is_string($element)) {
            // NOTICE: this is a hack for the odd 
	    //   connectivity API which is an array of strings.....
            $this->element = new stdClass();
            $this->element->id = $element;
        } else {
            $this->element = $element;
        }
    }

    /**
     *
     *
     */
    private function getElement() {
        return $this->element;
    }

    /**
     *
     *
     */
    private function getElementId() {
        // TODO: going to need to figure out how to make the id generic..
        if(!empty($this->element->device_id)) {
            return $this->element->device_id;
        }
        return $this->element->id;
    }

    /**
     *
     *
     */
    private function getEntityName() {
        return $this->entity_name;
    }

    /**
     *
     *
     */
    private function setEntityName($entity_name) {
        if (!class_exists($entity_name)) {
            $this->entity_name = null;
        } else {
            $this->entity_name = $entity_name;
        }
    }

    /**
     *
     *
     */
    private function getChain() {
        return $this->chain;
    }

    /**
     *
     *
     */
    private function setChain(ChainableInterface $chain) {
        $this->chain = $chain;
    }
}
