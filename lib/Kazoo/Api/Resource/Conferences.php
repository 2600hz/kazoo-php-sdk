<?php

namespace Kazoo\Api\Resource;

use Kazoo\Api\AbstractResource;

/**
 * 
 */
class Conferences extends AbstractResource {

    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Conference";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\ConferenceCollection";

}