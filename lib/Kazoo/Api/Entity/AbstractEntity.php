<?php

namespace Kazoo\Api\Entity;

use Kazoo\Api\ChainableInterface;

abstract class AbstractEntity extends \Kazoo\Api\AbstractResource {

    public function __construct(ChainableInterface $chain, array $arguments = array()) {
        parent::__construct($chain, $arguments);

        if (!empty($arguments[0])) {
            // Class name without the namespace
            $className = join('', array_slice(explode('\\', get_class($this)), -1));
            // Camel case to underscore
            $className = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $className));
            $tokenName = $className . "_id";
            $this->setToken($tokenName, $arguments[0]);
        }

        return $this;
    }

    public function fetch() {
        return $this->get();
    }

    public function save() {
        return $this->post($this->toJSON());
    }

    public function delete() {
        return $this->delete($this->toJSON());
    }

    public function __call($name, $arguments) {
        $collectionName = '\\Kazoo\\Api\\Collection\\' . $name;
        if (class_exists($collectionName)) {
            return new $collectionName($this, $arguments);
        }

        $entityName = '\\Kazoo\\Api\\Entity\\' . $name;
        if (class_exists($entityName)) {
            return new $entityName($this, $arguments);
        }
    }
}