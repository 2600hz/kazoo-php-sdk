<?php

namespace Kazoo\Api;

use \stdClass;

use Kazoo\HttpClient\Message\ResponseMediator;

/**
 * Abstract class for Api classes
 *
 */
abstract class AbstractResource {

    /**
     * The client
     *
     * @var \Kazoo\Client
     */
    protected $_client;

    /**
     * The uri_prefix
     *
     * @var null|string
     */
    protected $_uri;

    /**
     * number of items per page (Kazoo pagination)
     *
     * @var null|int
     */
    protected $perPage;

    /**
     *
     * @var array
     */
    protected $_child_resources;

    /**
     *
     * @param \Kazoo\Client $client
     * @param null|string $uri
     */
    public function __construct(\Kazoo\Client $client, $uri = null) {
        $this->_client = $client;
        $this->_uri = $uri;

        $this->_child_resources = array();
        $this->_child_resource_instances = array();
    }

    protected function hasChildResource($name) {
        return array_key_exists($name, $this->_child_resource_instances);
    }

    protected function getChildResource($name) {
        return $this->_child_resource_instances[$name];
    }

    public function initChildInstances() {
        foreach ($this->_child_resources as $child_resource_definition) {
            $type = "Kazoo\\Api\\Resource\\" . $child_resource_definition['resource_class'];
            $name = $child_resource_definition['name'];
            $uri = $child_resource_definition['uri'];
            $this->_child_resource_instances[$name] = new $type($this->_client, $this->_uri . $uri);
        }
    }

    public function __call($name, $arguments) {

        if ($this->hasChildResource($name)) {
            return $this->getChildResource($name);
        } else {
            switch (strtolower($name)) {
                case 'new':
                    $entity_class = static::$_entity_class;
                    return new $entity_class($this->_client, $this->_uri);
                    break;
                case 'get':
                case 'retrieve':
                    switch (count($arguments)) {
                        case 0:
                            $response = $this->_client->get($this->_uri, array());
                            $collection_type = static::$_entity_collection_class;
                            $raw_entity_list = $this->process_response($response);

                            if (static::$_entity_class == "Kazoo\Api\Data\Entity\Limit") {
                                $entity_class = static::$_entity_class;
                                return new $entity_class($this->_client, $this->_uri, $raw_entity_list);
                            }

                            $entity_list = array();
                            foreach($raw_entity_list as $raw_entity){
                                $entity_class = static::$_entity_class;
                                if (static::$_entity_class == "Kazoo\Api\Data\Entity\Registration") {
                                    $entityInstance = new $entity_class($this->_client, $this->_uri, $raw_entity);
                                } else if (static::$_entity_class == "Kazoo\Api\Data\Entity\Connectivity") {
                                    $connectivity = new stdClass();
                                    $connectivity->id = $raw_entity;
                                    $entityInstance = new $entity_class($this->_client, $this->_uri . "/" . $raw_entity);
                                    $entityInstance->partialUpdateFromResult($connectivity);
                                } else if (static::$_entity_class == "Kazoo\Api\Data\Entity\Cdr") {
                                    $entityInstance = new $entity_class($this->_client, $this->_uri . "/" . $raw_entity->id);
                                    $entityInstance->updateFromResult($raw_entity);
                                } else {
                                    $entityInstance = new $entity_class($this->_client, $this->_uri . "/" . $raw_entity->id);
                                    $entityInstance->partialUpdateFromResult($raw_entity);
                                }
                                $entity_list[] = $entityInstance;
                            }

                            return new $collection_type($entity_list);
                            break;
                        case 1:
                            if (is_string($arguments[0])) {
                                $resource_id = $arguments[0];
                                $result = $this->_client->get($this->_uri . "/" . urlencode($resource_id));
                                $entity_class = static::$_entity_class;
                                $entityInstance = new $entity_class($this->_client, $this->_uri . "/" . $resource_id);
                                return $entityInstance->updateFromResult($result->data);
                            } else if (is_array($arguments[0])) {
                                $filters = $arguments[0];

                                $response = $this->_client->get($this->_uri, $filters);
                                $collection_type = static::$_entity_collection_class;
                                $raw_entity_list = $this->process_response($response);

                                $entity_list = array();
                                foreach($raw_entity_list as $raw_entity){
                                    $entity_class = static::$_entity_class;
                                    $entityInstance = new $entity_class($this->_client, $this->_uri . "/" . $raw_entity->id);
                                    $entityInstance->partialUpdateFromResult($raw_entity);
                                    $entity_list[] = $entityInstance;
                                }

                                return new $collection_type($entity_list);
                            }
                            break;
                    }
                    break;
            }
        }
    }

    private function process_response($response) {
        $results = $response->data;
        if (is_object($results)
          && property_exists($results, "numbers")) {
            $results = $results->numbers;
            foreach($results as $key => $value) {
                $results->$key->id = urlencode($key);
            }
        }
        return $results;
    }
}
