<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class QuickCalls extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\QuickCall";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\QuickCallCollection";
    protected static $_schema_name = "clicktocall.json";
    
    public function __construct(\Kazoo\Client $client, $uri) {
        parent::__construct($client, $uri);
    }
    
}