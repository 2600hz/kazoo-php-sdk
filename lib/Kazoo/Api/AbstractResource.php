<?php

namespace Kazoo\Api;

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
    protected $client;

    /**
     * The uri_prefix
     *
     * @var null|string
     */
    protected $uri;

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
        $this->client = $client;
        $this->uri = $uri;
        
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
            $this->_child_resource_instances[$name] = new $type($this->client, $this->uri . $uri);
        }
    }
    
    public function __call($name, $arguments) {

        if ($this->hasChildResource($name)) {
            return $this->getChildResource($name);
        } else {
            switch (strtolower($name)) {
                case 'new':
                    $entityInstance = new $entity_class($this->client, $this->uri);
                    return JsonSchemaObjectFactory::hydrateNew($entityInstance);
                    break;
                case 'create':
                    if ($arguments[0] instanceof \Kazoo\Api\Data\AbstractEntity) {
                        $account = $arguments[0];
                        $result = $this->client->put($this->uri, $account->getData());
                        return $account->updateFromResult($result->data);
                    }
                    break;
                case 'retrieve':
                    switch (count($arguments)) {
                        case 0:
                            $response = $this->client->get($this->uri, array());
                            $collection_type = static::$_entity_collection_class;
                            return new $collection_type($response->data);
                            break;
                        case 1:
                            if (is_string($arguments[0])) {
                                $resource_id = $arguments[0];
                                return $this->client->get($this->uri . "/" . $resource_id, array());
                            } else if (is_array($arguments[0])) {
                                $filters = $arguments[0];
                                return $this->client->get($this->uri, $filters);
                            }
                            break;
                    }
                    break;
            }
        }
    }

}
