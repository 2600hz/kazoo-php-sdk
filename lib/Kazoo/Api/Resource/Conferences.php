<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class Conferences extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Conference";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\ConferenceCollection";
    protected static $_schema_name = "conferences.json";
    
    public function __construct(\Kazoo\Client $client, $uri) {
        parent::__construct($client, $uri);
    }
    
}