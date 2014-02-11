<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class Media extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Media";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\MediaCollection";
    
}