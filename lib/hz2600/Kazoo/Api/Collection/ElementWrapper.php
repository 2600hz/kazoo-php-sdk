<?php

namespace Kazoo\Api\Collection;

use \Exception;
use \stdClass;

use \Kazoo\Common\ChainableInterface;
use \Kazoo\Common\Exception\ReadOnly;
use \Kazoo\Api\Exception\Unfetchable;

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
    private $element_id;

    /**
     *
     *
     */
    private $fetchable = TRUE;

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
        throw new ReadOnly("Collection elements are read-only");
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
        if (!$this->fetchable) {
            throw new Unfetchable("This collection element has no corresponing API for the entity");
        }

        $entity_name = $this->getEntityName();

        if (is_null($entity_name)) {
            throw new ReadOnly("This is a read only API");
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
    public function setElement(&$element, $id = null) {
        $this->element_id = $id;
        $this->element = $element;

        // TODO: going to need to figure out how to make the id generic..
        if(!empty($element->device_id)) {
            return $this->element_id = $element->device_id;
        }

        if (!empty($element->uuid)){
           return $this->element_id = $element->uuid; 
        }


        // NOTICE: this is a hack for the odd
        //   connectivity API which is an array of strings.....
        if(is_string($element)) {
            $this->element = new stdClass();
            $this->element_id = $element;
        }
    }

    /**
     *
     *
     */
    public function fetchable() {
        $this->fetchable = TRUE;
    }

    /**
     *
     *
     */
    public function unfetchable() {
        $this->fetchable = FALSE;
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
        if (is_null($this->element_id)) {
            return $this->element->id;
        }

        return $this->element_id;
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
        if (!@class_exists($entity_name)) {
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
