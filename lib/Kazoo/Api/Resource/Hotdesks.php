<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class Hotdesks extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Hotdesk";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\HotdeskCollection";
    protected static $_schema_name = "clicktocall.json";
    
    public function __construct(\Kazoo\Client $client, $uri) {
        parent::__construct($client, $uri);
    }
    
}