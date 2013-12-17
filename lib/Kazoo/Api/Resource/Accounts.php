<?php

namespace Kazoo\Api\Resource;

use Kazoo\Api\JsonSchemaObjectFactory;
use Kazoo\Api\AbstractResource;

/**
 * Creating, editing, deleting and listing accounts
 *
 * @link   https://2600hz.atlassian.net/wiki/display/docs/Accounts+API
 */
class Accounts extends AbstractResource {

    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Account";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\AccountCollection";
    protected static $_schema_name = "accounts.json";

    public function __construct(\Kazoo\Client $client, $uri) {
        parent::__construct($client, $uri);
        $this->defineChildApis();
        $this->initChildInstances();
    }

    public function defineChildApis() {
        $this->_child_resources[] = array("name" => "callflows", "uri" => "/callflows", "resource_class" => "Callflows");
        $this->_child_resources[] = array("name" => "carrier_resources", "uri" => "/carrier_resources", "resource_class" => "CarrierResources");
        $this->_child_resources[] = array("name" => "cdrs", "uri" => "/cdrs", "resource_class" => "Cdrs");
        $this->_child_resources[] = array("name" => "clicktocalls", "uri" => "/clicktocall", "resource_class" => "ClickToCalls");
        $this->_child_resources[] = array("name" => "conferences", "uri" => "/conferences", "resource_class" => "Conferences");
        $this->_child_resources[] = array("name" => "devices", "uri" => "/devices", "resource_class" => "Devices");
        $this->_child_resources[] = array("name" => "directories", "uri" => "/directories", "resource_class" => "Directories");
        $this->_child_resources[] = array("name" => "faxes", "uri" => "/faxes", "resource_class" => "Faxes");
        $this->_child_resources[] = array("name" => "hotdesks", "uri" => "/hotdesks", "resource_class" => "Hotdesks");
        $this->_child_resources[] = array("name" => "quickcalls", "uri" => "/quickcalls", "resource_class" => "Quickcalls");
        $this->_child_resources[] = array("name" => "menus", "uri" => "/menus", "resource_class" => "Menus");
        $this->_child_resources[] = array("name" => "agents", "uri" => "/agents", "resource_class" => "Agents");
        $this->_child_resources[] = array("name" => "queues", "uri" => "/queues", "resource_class" => "Queues");
        $this->_child_resources[] = array("name" => "media", "uri" => "/media", "resource_class" => "Media");
        $this->_child_resources[] = array("name" => "users", "uri" => "/users", "resource_class" => "Users");
        $this->_child_resources[] = array("name" => "timed_routes", "uri" => "/temporal_rules", "resource_class" => "TimedRoutes");
        $this->_child_resources[] = array("name" => "groups", "uri" => "/groups", "resource_class" => "RingGroups");
        $this->_child_resources[] = array("name" => "registrations", "uri" => "/registrations", "resource_class" => "Registrations");
        $this->_child_resources[] = array("name" => "voicemail_boxes", "uri" => "/vmboxes", "resource_class" => "VoicemailBoxes");
        $this->_child_resources[] = array("name" => "webhooks", "uri" => "/webhooks", "resource_class" => "Webhooks");
        $this->_child_resources[] = array("name" => "phone_numbers", "uri" => "/phone_numbers", "resource_class" => "PhoneNumbers");
    }

    public function __call($name, $arguments) {
        
        if ($this->hasChildResource($name)) {
            return $this->getChildResource($name);
        } else {
            switch (strtolower($name)) {
                case 'new':
                    return JsonSchemaObjectFactory::getNew($this->client, $this->uri, self::$_entity_class, $this->getSchemaJson());
                    break;
                case 'create':
                    if ($arguments[0] instanceof \Kazoo\Api\Data\AbstractEntity) {
                        $account = $arguments[0];
                        $result = $this->client->put($this->uri, $account->getData());
                        return $account->updateFromResult($result);
                    }
                    break;
                case 'get':
                case 'retrieve':
                    switch (count($arguments)) {
                        case 0:
                            return $this->client->get($this->uri . "/descendants");
                            break;
                        case 1:
                            if (is_int($arguments[0])) {
                                $resource_id = $arguments[0];
                                $this->client->setCurrentAccountContext($resource_id);
                                return $this->client->get($this->uri, array());
                            } else if (is_array($arguments[0])) {
                                $filters = $arguments[0];
                                return $this->client->get($this->uri, $filters);
                            }
                            break;
                    }
                    break;
            }
        }
    }

}
