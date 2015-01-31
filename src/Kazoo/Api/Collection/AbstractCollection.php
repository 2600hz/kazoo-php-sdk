<?php

namespace Kazoo\Api\Collection;

use \Iterator;
use \Countable;
use \ArrayAccess;

use \Kazoo\Common\Utils;
use \Kazoo\Common\ChainableInterface;
use \Kazoo\Common\Exception\ReadOnly;
use \Kazoo\Api\AbstractResource;

abstract class AbstractCollection extends AbstractResource implements Iterator, Countable, ArrayAccess
{
    /**
     *
     *
     */
    private $element_wrapper;

    /**
     *
     *
     */
    private $collection;

    /**
     *
     *
     */
    private $keys;

    /**
     *
     *
     */
    private $default_filter = array();

    /**
     *
     *
     */
    public function __construct(ChainableInterface $chain, array $arguments = array()) {
        parent::__construct($chain, $arguments);

        $entity_name = $this->getEntityName();
        $this->setElementWrapper(new ElementWrapper($chain, $entity_name));

        if (count($arguments) > 1) {
            throw new Exception("invalid arguments...");
        }

        if (isset($arguments[0])) {
            $this->setDefaultFilter($arguments[0]);
        }
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
        throw new ReadOnly("Collections are read-only");
    }

    /**
     *
     *
     */
    public function fetch(array $filter = array()) {
        $response = $this->get($this->getFilter($filter));
        $this->setCollection($response->getData());
        $this->rewind();
        return $this;
    }

    /**
     *
     *
     */
    public function rewind() {
        $collection = $this->getCollection();
        $this->keys = array_keys((array)$collection);
    }

    /**
     *
     *
     */
    public function current() {
        $key = $this->key();
        $collection = $this->getCollection();

        if (is_array($collection)) {
            return $this->loadElementWrapper($collection[$key], $key);
        } else if (is_object($collection)) {
            return $this->loadElementWrapper($collection->$key, $key);
        }

        return null;
    }

    /**
     *
     *
     */
    public function key() {
        if (is_null($this->keys)) {
            $this->fetch();
        }

        return current($this->keys);
    }

    /**
     *
     *
     */
    public function next() {
        if (is_null($this->keys)) {
            $this->fetch();
        }

        next($this->keys);
    }

    /**
     *
     *
     */
    public function valid() {
        return $this->key() !== false;
    }

    /**
     *
     *
     */
    public function count() {
        if (is_null($this->keys)) {
            $this->fetch();
        }

        return count($this->keys);
    }

    /**
     *
     *
     */
    public function offsetSet($offset, $value) {
        throw new ReadOnly("Collections are read only");
    }

    /**
     *
     *
     */
    public function offsetExists($offset) {
        return isset($this->keys[$offset]);
    }

    /**
     *
     *
     */
    public function offsetUnset($offset) {
        $collection = $this->getCollection();
        $key = $this->keys[$offset];

        if (is_array($collection)) {
            unset($collection[$key]);
        } else if (is_object($collection)) {
            unset($collection->$key);
        }

        unset($this->keys[$offset]);
    }

    /**
     *
     *
     */
    public function offsetGet($offset) {
        $collection = $this->getCollection();
        $key = $this->keys[$offset];

        if (is_array($collection)) {
            return $this->loadElementWrapper($collection[$key], $key);
        } else if (is_object($collection)) {
            return $this->loadElementWrapper($collection->$key, $key);
        }

        return null;
    }

    /**
     *
     *
     */
    public function toJson() {
        return (string)json_encode($this->getCollection());
    }

    /**
     *
     *
     */
    public function getFilter(array $filter = array()) {
        return array_merge($this->getDefaultFilter(), $filter);
    }

    /**
     *
     *
     */
    public function setDefaultFilter(array $filter = array()) {
        return $this->default_filter = $filter;
    }

    /**
     *
     *
     */
    protected function getDefaultFilter() {
        return $this->default_filter;
    }

    /**
     *
     *
     */
    protected function getUriSnippet() {
        return '/' . Utils::underscoreClassName($this);
    }

    /**
     *
     *
     */
    protected function getEntityName() {
        $className = Utils::shortClassName($this);
        return Utils::depluralize($className);
    }

    /**
     *
     *
     */
    protected function getCollection() {
        if (is_null($this->collection)) {
            $this->fetch();
        }

        return $this->collection;
    }

    /**
     *
     *
     */
    protected function setCollection($collection) {
        $this->collection = $collection;
        $this->rewind();
    }

    /**
     *
     *
     */
    protected function getElementWrapper() {
        return $this->element_wrapper;
    }

    /**
     *
     *
     */
    protected function loadElementWrapper($element, $key) {
        $element_wrapper = $this->getElementWrapper();
        $element_wrapper->setElement($element);
        return $element_wrapper;
    }

    /**
     *
     *
     */
    private function setElementWrapper(ElementWrapper $wrapper) {
        $this->element_wrapper = $wrapper;
    }
}