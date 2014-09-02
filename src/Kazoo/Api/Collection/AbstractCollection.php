<?php

namespace Kazoo\Api\Collection;

use \Iterator;
use \Countable;

use \Kazoo\Common\Utils;
use \Kazoo\Common\ChainableInterface;
use \Kazoo\Api\AbstractResource;

abstract class AbstractCollection extends AbstractResource implements Iterator, Countable
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
        $element_wrapper = $this->getElementWrapper();

        if (is_array($collection)) {
            $element = $collection[$key];
        } else if (is_object($collection)) {
            $element = $collection->$key;
        } else {
            return null;
        }

        $element_wrapper->setElement($element);

        return $element_wrapper;
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
4     */
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
    }

    /**
     *
     *
     */
    private function setElementWrapper(ElementWrapper $wrapper) {
        $this->element_wrapper = $wrapper;
    }

    /**
     *
     *
     */
    private function getElementWrapper() {
        return $this->element_wrapper;
    }
}