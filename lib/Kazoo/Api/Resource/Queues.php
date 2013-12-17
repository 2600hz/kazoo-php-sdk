<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class Queues extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Queue";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\QueueCollection";
    protected static $_schema_name = "phone_numbers.json";
    
    public function __construct(\Kazoo\Client $client, $uri) {
        parent::__construct($client, $uri);
    }
    
}