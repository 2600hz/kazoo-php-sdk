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
    private $elementWrapper;

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
    public function __construct(ChainableInterface $chain, array $arguments = array()) {
        parent::__construct($chain, $arguments);

        $entity_name = $this->getEntityName();
        $this->setElementWrapper(new ElementWrapper($chain, $entity_name));

        if (count($arguments) > 1) {
            throw new Exception("invalid arguments...");
        }

        if (isset($arguments[0])) {
            $this->fetch($arguments[0]);
        } else {
            $this->fetch();
        }
    }

    /**
     *
     *
     */
    public function fetch(array $filter = array()) {
        $response = $this->get($filter);
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
        $elementWrapper = $this->getElementWrapper();

        if (is_array($collection)) {
            $element = $collection[$key];
        } else if (is_object($collection)) {
            $element = $collection[$key];
        } else {
            return null;
        }

        return $elementWrapper->setElement($element);
    }

    /**
     *
     *
     */
    public function key() {
        return current($this->keys);
    }

    /**
     *
     *
     */
    public function next() {
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
        return count($this->keys);
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
    private function getCollection() {
        return $this->collection;
    }

    /**
     *
     *
     */
    private function setCollection($collection) {
        $this->collection = $collection;
    }

    /**
     *
     *
     */
    private function setElementWrapper(ElementWrapper $wrapper) {
        $this->elementWrapper = $wrapper;
    }

    /**
     *
     *
     */
    private function getElementWrapper() {
        return $this->elementWrapper;
    }
}