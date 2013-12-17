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
     * @var null|string
     */
    protected $_schema_json;

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
        $this->_schema_json = null;
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

    public function getSchemaJson() {
        $this->_schema_json = file_get_contents($this->client->getOption('schema_dir') . "/" . static::$_schema_name);
        return $this->_schema_json;
    }

    public function __call($name, $arguments) {

        if ($this->hasChildResource($name)) {
            return $this->getChildResource($name);
        } else {
            switch (strtolower($name)) {
                case 'new':
                    return JsonSchemaObjectFactory::getNew($this->client, $this->uri, static::$_entity_class, $this->getSchemaJson());
                    break;
                case 'create':
                    if ($arguments[0] instanceof \Kazoo\Api\Data\AbstractEntity) {
                        $account = $arguments[0];
                        $result = $this->client->put($this->uri, $account->getData());
                        return $account->updateFromResult($result);
                    }
                    break;
                case 'get':
                case 'retrieve':
                    switch (count($arguments)) {
                        case 0:
                            return $this->client->get($this->uri);
                            break;
                        case 1:
                            if (is_int($arguments[0])) {
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
