<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class Servers extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Server";
    protected static $_entity_collection_class = "\\Kazoo\\Api\\Data\\Collection\\ServerCollection";
    
}