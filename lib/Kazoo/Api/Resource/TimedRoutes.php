<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class TimedRoutes extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\TimeRoute";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\TimedRouteCollection";
    protected static $_schema_name = "temporal_routes.json";
    
    public function __construct(\Kazoo\Client $client, $uri) {
        parent::__construct($client, $uri);
    }
    
}