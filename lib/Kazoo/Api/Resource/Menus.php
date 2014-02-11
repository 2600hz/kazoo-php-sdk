<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class Menus extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Menu";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\MenuCollection";
    
}