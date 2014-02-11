<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class Hotdesks extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Hotdesk";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\HotdeskCollection";

}