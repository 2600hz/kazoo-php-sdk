<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class Directories extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Directory";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\Directories";
    protected static $_schema_name = "";
    
    public function __construct(\Kazoo\Client $client, $uri) {
        parent::__construct($client, $uri);
    }
    
}