<?php

namespace Kazoo\Api\Collection;

use \Exception;
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
        $this->chain = $chain;

        $entity_name = '\\Kazoo\\Api\\Entity\\' . $name;
        if (!class_exists($entity_name)) {
            throw new Exception("no such entity $entity_name");
        }

        $this->entity_name = $entity_name;
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
    public function fetch() {
        $entity_name = $this->entity_name;
        // TODO: going to need to figure out how to make the id generic..
        return new $entity_name($this->chain, array($this->element->id));
    }

    /**
     *
     *
     */
    public function setElement(&$element) {
        $this->element = $element;
        return $this;
    }

    /**
     *
     *
     */
    public function __set($name, $value) {
        throw new Exception("collection elements");
    }

    /**
     *
     *
     */
    public function &__get($name) {
        return $this->element->$name;
    }

    /**
     *
     *
     */
    public function toJson() {
        return (string)json_encode($this->element);
    }
}