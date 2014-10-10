<?php

namespace Kazoo\Api\Resource;

use Kazoo\Api\AbstractResource;

/**
 *
 */
class Cdrs extends AbstractResource {
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Cdr";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\CdrCollection";
}
