<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class Users extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\User";
    protected static $_entity_collection_class = "\\Kazoo\\Api\\Data\\Collection\\UserCollection";
    
}