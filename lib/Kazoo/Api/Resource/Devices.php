<?php

namespace Kazoo\Api\Resource;

use Kazoo\Api\AbstractResource;

/**
 * 
 */
class Devices extends AbstractResource {

    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Device";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\DeviceCollection";

}