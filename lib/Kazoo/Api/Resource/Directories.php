<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class Directories extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Directory";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\DirectoryCollection";

}