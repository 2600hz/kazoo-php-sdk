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
        $this->_child_resources[] = array("name" => "callflows", "uri" => "/{account_id}/callflows", "resource_class" => "Callflows");
        $this->_child_resources[] = array("name" => "carrier_resources", "uri" => "/{account_id}/carrier_resources", "resource_class" => "CarrierResources");
        $this->_child_resources[] = array("name" => "cdrs", "uri" => "/{account_id}/cdrs", "resource_class" => "Cdrs");
        $this->_child_resources[] = array("name" => "clicktocalls", "uri" => "/{account_id}/clicktocall", "resource_class" => "ClickToCalls");
        $this->_child_resources[] = array("name" => "conferences", "uri" => "/{account_id}/conferences", "resource_class" => "Conferences");
        $this->_child_resources[] = array("name" => "devices", "uri" => "/{account_id}/devices", "resource_class" => "Devices");
        $this->_child_resources[] = array("name" => "directories", "uri" => "/{account_id}/directories", "resource_class" => "Directories");
        $this->_child_resources[] = array("name" => "faxes", "uri" => "/{account_id}/faxes", "resource_class" => "Faxes");
        $this->_child_resources[] = array("name" => "hotdesks", "uri" => "/{account_id}/hotdesks", "resource_class" => "Hotdesks");
        $this->_child_resources[] = array("name" => "quickcalls", "uri" => "/{account_id}/quickcalls", "resource_class" => "Quickcalls");
        $this->_child_resources[] = array("name" => "menus", "uri" => "/{account_id}/menus", "resource_class" => "Menus");
        $this->_child_resources[] = array("name" => "agents", "uri" => "/{account_id}/agents", "resource_class" => "Agents");
        $this->_child_resources[] = array("name" => "queues", "uri" => "/{account_id}/queues", "resource_class" => "Queues");
        $this->_child_resources[] = array("name" => "media", "uri" => "/{account_id}/media", "resource_class" => "Media");
        $this->_child_resources[] = array("name" => "users", "uri" => "/{account_id}/users", "resource_class" => "Users");
        $this->_child_resources[] = array("name" => "timed_routes", "uri" => "/{account_id}/temporal_rules", "resource_class" => "TimedRoutes");
        $this->_child_resources[] = array("name" => "groups", "uri" => "/{account_id}/groups", "resource_class" => "RingGroups");
        $this->_child_resources[] = array("name" => "registrations", "uri" => "/{account_id}/registrations", "resource_class" => "Registrations");
        $this->_child_resources[] = array("name" => "voicemail_boxes", "uri" => "/{account_id}/vmboxes", "resource_class" => "VoicemailBoxes");
        $this->_child_resources[] = array("name" => "webhooks", "uri" => "/{account_id}/webhooks", "resource_class" => "Webhooks");
        $this->_child_resources[] = array("name" => "phone_numbers", "uri" => "/{account_id}/phone_numbers", "resource_class" => "PhoneNumbers");
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
                    if (is_array($arguments[0])) {
                        return $this->client->put($this->uri, json_encode($arguments[0]));
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
