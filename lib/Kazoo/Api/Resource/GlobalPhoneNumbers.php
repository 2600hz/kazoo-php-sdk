<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class GlobalPhoneNumbers extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\PhoneNumber";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\PhoneNumberCollection";
    protected static $_schema_name = "phone_numbers.json";
    
}