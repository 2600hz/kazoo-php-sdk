<?php

namespace Kazoo\Api\Resource;
use Kazoo\Api\AbstractResource;

/**
 * 
 */
class Webhooks extends AbstractResource {
    
    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Webhook";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\WebhookCollection";
    
}