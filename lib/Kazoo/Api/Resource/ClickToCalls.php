<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class ClickToCalls extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\ClickToCall";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\ClickToCallCollection";
    protected static $_schema_name = "clicktocall.json";
    
    public function __construct(\Kazoo\Client $client, $uri) {
        parent::__construct($client, $uri);
    }
    
}