<?php

namespace Kazoo\Api\Resource;

use Kazoo\Api\AbstractResource;

/**
 * 
 */
class Cdrs extends AbstractResource {

    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Cdr";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\CdrCollection";

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

                            $entity_list = array();
                            foreach($raw_entity_list as $raw_entity){
                                $entity_class = static::$_entity_class;
                                $entityInstance = new $entity_class($this->_client, $this->_uri . "/" . $raw_entity->id);
                                $entityInstance->updateFromResult($raw_entity);
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
                                    $entityInstance->updateFromResult($raw_entity);
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
}
