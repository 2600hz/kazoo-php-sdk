<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class TimeBasedRoutes extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\TimeBasedRoute";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\TimeBasedRouteCollection";

}