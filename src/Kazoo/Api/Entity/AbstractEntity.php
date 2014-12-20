<?php

namespace Kazoo\Api\Entity;

use \stdClass;
use \Exception;
use \BadFunctionCallException;
use \RuntimeException;

use \Kazoo\Common\Utils;
use \Kazoo\Common\ChainableInterface;
use \Kazoo\HttpClient\Message\Response;
use \Kazoo\Api\AbstractResource;

abstract class AbstractEntity extends AbstractResource
{
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
        $collection_name = '\\Kazoo\\Api\\Collection\\' . $name;
        if (class_exists($collection_name)) {
            return new $collection_name($this, $arguments);
        }

        $entity_name = '\\Kazoo\\Api\\Entity\\' . $name;
        if (class_exists($entity_name)) {
            return new $entity_name($this, $arguments);
        }

        $backtrace = debug_backtrace();
        $filename = $backtrace[0]['file'];
        $line = $backtrace[0]['line'];
        $class_name = get_class($this);

        $message = "Call to undefined method $class_name::$name in $filename on line $line";
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
    public function __unset($name) {
        unset($this->entity->$name);
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
     * Explicitly fetch from Kazoo, typicall it lazy-loads.
     * This could also be used to re-load to ensure the data
     * is fresh.
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
     * Saves the current entity, if it does not have an
     * id then it will be created.
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
     * Remove the entity.  Note: it is called remove
     * because the parent has a delete function and
     * parent::delete() irked me when the reset are
     * $this->put or $this->post.
     *
     */
    public function remove() {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        $this->delete();
        $this->reset();
        return $this;
    }

    /**
     * Allows you to use this entity as a template.
     * For example, you could create a device with all
     * the settings you want.  Then using duplicate
     * could could save 4 variations of that device
     * without having to re-set the common properties.
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
     * Reset the entity locally, becomes an empty
     * container to create a new entity.
     *
     */
    public function reset() {
        $this->setEntity();
        return $this;
    }

    /**
     * Automagic used to determine the URL snippet.
     * This should be implemented in the entity class if
     * it is an exception to the standard naming.
     *
     */
    protected function getUriSnippet() {
        $collectionName = $this->getCollectionName();
        $entityIdName = $this->getEntityIdName();
        return "/$collectionName/{{$entityIdName}}";
    }

    /**
     * Automagic used to determine the entity id name
     * for use in the URL snippet.  This should
     * be implemented in the entity class if
     * it is an exception to the standard naming.
     *
     */
    protected function getEntityIdName() {
        $entityName = Utils::underscoreClassName($this);
        return $entityName . "_id";
    }

    /**
     * Automagic used to determine the collection name
     * for use in the URL snippet.  This should
     * be implemented in the entity class if
     * it is an exception to the standard naming.
     *
     */
    protected function getCollectionName() {
        $entityName = Utils::underscoreClassName($this);
        return Utils::pluralize($entityName);
    }

    /**
     *
     *
     */
    protected function setEntity($entity = null) {
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
    private function getPayload() {
        $shell = new stdClass();
        $shell->data = $this->getEntity();

        return json_encode($shell);
    }
}
