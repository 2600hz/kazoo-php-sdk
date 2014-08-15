<?php

namespace Kazoo\Api\Entity;

use \stdClass;
use \Exception;
use \BadFunctionCallException;
use \RuntimeException;

use \Kazoo\Common\ChainableInterface;
use \Kazoo\HttpClient\Message\Response;

abstract class AbstractEntity extends \Kazoo\Api\AbstractResource {
    /**
     *
     *
     */
    protected $entity;

    /**
     *
     *
     */
    protected $entity_id;

    /**
     *
     *
     */
    public function __construct(ChainableInterface $chain, array $arguments = array()) {
        parent::__construct($chain, $arguments);
        $this->invoke($arguments);
        return $this;
    }

    /**
     *
     *
     */
    public function __invoke() {
        $this->invoke(func_get_args());
        return $this;
    }

    /**
     *
     *
     */
    public function __call($name, $arguments) {
        $collectionName = '\\Kazoo\\Api\\Collection\\' . $name;
        if (class_exists($collectionName)) {
            return new $collectionName($this, $arguments);
        }

        $entityName = '\\Kazoo\\Api\\Entity\\' . $name;
        if (class_exists($entityName)) {
            return new $entityName($this, $arguments);
        }

        $backtrace = debug_backtrace();
        $filename = $backtrace[0]['file'];
        $line = $backtrace[0]['line'];
        $className = get_class($this);

        $message = "Call to undefined method $className::$name in $filename on line $line";
        throw new BadFunctionCallException($message);
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
        if(is_null($this->entity)) {
            $id = $this->getId();
            if (!empty($id)) {
                $this->fetch();
            } else {
                $this->setEntity(new stdClass);
            }
        }
        $this->entity->$name = $value;
    }

    /**
     *
     *
     */
    public function &__get($name) {
        $id = $this->getId();
        if(is_null($this->entity) && !empty($id)) {
            $this->fetch();
        }
        return $this->entity->$name;
    }

    /**
     *
     *
     */
    public function __isset($name) {
        return isset($this->entity->$name);
    }

    /**
     *
     *
     */
    public function __clone() {
        if (!is_null($this->entity)) {
            $this->entity = clone $this->entity;
        }

        $this->setId();
    }

    /**
     *
     *
     */
    public function fetch() {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());

        $response = $this->get();

        $entity = $response->getData();
        $this->setEntity($entity);

        return $this;
    }

    /**
     *
     *
     */
    public function save() {
        $id = $this->getId();
        $payload = $this->getPayload();

        $this->setTokenValue($this->getEntityIdName(), $id);

        if (empty($id)) {
            $response = $this->put($payload);
        } else {
            $response = $this->post($payload);
        }

        $entity = $response->getData();
        $this->setEntity($entity);

        return $this;
    }

    /**
     *
     *
     */
    public function remove() {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        $this->delete();
        $this->reset();
        return $this;
    }

    /**
     *
     *
     */
    public function duplicate() {
        return clone $this;
    }

    /**
     *
     *
     */
    public function toJson() {
        return (string)json_encode($this->getEntity());
    }

    /**
     *
     *
     */
    public function fromJson($json) {
        $entity = json_decode((string)$json);
        if (json_last_error() === JSON_ERROR_NONE && !empty($json)) {
            $this->setEntity($entity);
            return $this;
        }

        throw new RuntimeException('unable to parse JSON: ' . json_last_error());
    }

    /**
     *
     *
     */
    public function getId() {
        return ($this->entity_id ?: "");
    }

    /**
     *
     *
     */
    public function reset() {
        $this->setEntity();
        return $this;
    }

    /**
     *
     *
     */
    protected function getEntityIdName() {
        // Class name without the namespace
        $className = join('', array_slice(explode('\\', get_class($this)), -1));
        // Camel case to underscore
        $className = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $className));
        return $className . "_id";
    }

    /**
     *
     *
     */
    private function invoke(array $arguments) {
        $entity_id = isset($arguments[0]) ? $arguments[0] : null;
        $this->setId($entity_id);
    }

    /**
     *
     *
     */
    private function setId($entity_id = null) {
        $this->entity_id = $entity_id;

        if (empty($entity_id)) {
            unset($this->entity->id);
        }
    }

    /**
     *
     *
     */
    private function getEntity() {
        $id = $this->getId();
        if(is_null($this->entity) && !empty($id)) {
            $this->fetch();
        }
        return ($this->entity ?: new stdClass);
    }

    /**
     *
     *
     */
    private function setEntity($entity = null) {
        $this->entity = $entity;
        if (!empty($entity->id)) {
            $this->setId($entity->id);
        } else {
            $this->setId();
        }
    }

    /**
     *
     *
     */
    private function getPayload() {
        $shell = new stdClass();
        $shell->data = $this->getEntity();

        return json_encode($shell);
    }
}
