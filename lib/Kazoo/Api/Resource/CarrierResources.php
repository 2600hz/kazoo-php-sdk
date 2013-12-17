<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class CarrierResources extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\CarrierResource";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\CarrierResourceCollection";
    protected static $_schema_name = "local_resources.json";
    
    public function __construct(\Kazoo\Client $client, $uri) {
        parent::__construct($client, $uri);
    }
    
}
