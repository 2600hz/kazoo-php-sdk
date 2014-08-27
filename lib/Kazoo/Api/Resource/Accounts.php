<?php

namespace Kazoo\Api\Resource;

use Kazoo\Api\JsonSchemaObjectFactory;
use Kazoo\Api\AbstractResource;
use Kazoo\Api\Data\Entity\Account;

/**
 * Creating, editing, deleting and listing accounts
 *
 * @link   https://2600hz.atlassian.net/wiki/display/docs/Accounts+API
 */
class Accounts extends AbstractResource {

    protected static $_entity_class = "Kazoo\\Api\\Data\\Entity\\Account";
    protected static $_entity_collection_class = "Kazoo\\Api\\Data\\Collection\\AccountCollection";

    public function __construct(\Kazoo\Client $client, $uri) {
        parent::__construct($client, $uri);
        $this->defineChildApis();
        $this->initChildInstances();
    }

    public function defineChildApis() {
        $this->_child_resources[] = array("name" => "agents", "uri" => "/agents", "resource_class" => "Agents");
        $this->_child_resources[] = array("name" => "callflows", "uri" => "/callflows", "resource_class" => "Callflows");
        $this->_child_resources[] = array("name" => "carrier_resources", "uri" => "/carrier_resources", "resource_class" => "CarrierResources");
        $this->_child_resources[] = array("name" => "cdrs", "uri" => "/cdrs", "resource_class" => "Cdrs");
        $this->_child_resources[] = array("name" => "clicktocalls", "uri" => "/clicktocall", "resource_class" => "ClickToCalls");
        $this->_child_resources[] = array("name" => "conferences", "uri" => "/conferences", "resource_class" => "Conferences");
        $this->_child_resources[] = array("name" => "connectivity", "uri" => "/connectivity", "resource_class" => "Connectivity");
        $this->_child_resources[] = array("name" => "devices", "uri" => "/devices", "resource_class" => "Devices");
        $this->_child_resources[] = array("name" => "limits", "uri" => "/limits", "resource_class" => "Limits");
        $this->_child_resources[] = array("name" => "directories", "uri" => "/directories", "resource_class" => "Directories");
        $this->_child_resources[] = array("name" => "faxes", "uri" => "/faxes", "resource_class" => "Faxes");
        $this->_child_resources[] = array("name" => "groups", "uri" => "/groups", "resource_class" => "RingGroups");
        $this->_child_resources[] = array("name" => "menus", "uri" => "/menus", "resource_class" => "Menus");
        $this->_child_resources[] = array("name" => "media", "uri" => "/media", "resource_class" => "Media");
        $this->_child_resources[] = array("name" => "users", "uri" => "/users", "resource_class" => "Users");
        $this->_child_resources[] = array("name" => "queues", "uri" => "/queues", "resource_class" => "Queues");
        $this->_child_resources[] = array("name" => "registrations", "uri" => "/registrations", "resource_class" => "Registrations");
        $this->_child_resources[] = array("name" => "servers", "uri" => "/servers", "resource_class" => "Servers");
        $this->_child_resources[] = array("name" => "temporal_rules_sets", "uri" => "/temporal_rules_sets", "resource_class" => "TemporalRulesSets");
        $this->_child_resources[] = array("name" => "timed_routes", "uri" => "/temporal_rules", "resource_class" => "TimeBasedRoutes");
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
                    return new Account($this->_client, $this->_uri);
                    break;
                case 'get':
                case 'retrieve':
                    switch (count($arguments)) {
                        case 0:
                            $response = $this->_client->get($this->_uri, array());
                            $collection_type = static::$_entity_collection_class;
                            $raw_entity_list = $response->data;

                            $entity_list = array();
                            foreach($raw_entity_list as $raw_entity){
                                $entity_class = static::$_entity_class;
                                $entityInstance = new $entity_class($this->_client, $this->_uri . "/" . $raw_entity->id);
                                $entityInstance->partialUpdateFromResult($raw_entity);
                                $entity_list[] = $entityInstance;
                            }

                            return new $collection_type($entity_list);
                            break;
                        case 1:
                            if (is_string($arguments[0])) {
                                $resource_id = $arguments[0];

                                $curAccountContext = $this->_client->getAccountContext();

                                $this->_client->setAccountContext($resource_id);

                                $result = $this->_client->get($this->_uri);

                                $this->_client->setAccountContext($curAccountContext);

                                $entity_class = static::$_entity_class;

                                $entityInstance = new $entity_class($this->_client, $this->_uri);

                                $entityInstance->updateFromResult($result->data);

                                return $entityInstance;
                            } else if (is_array($arguments[0])) {
                                $filters = $arguments[0];

                                $response = $this->_client->get($this->_uri, $filters);
                                $collection_type = static::$_entity_collection_class;
                                $raw_entity_list = $response->data;

                                $entity_list = array();
                                foreach($raw_entity_list as $raw_entity){
                                    $entity_class = static::$_entity_class;
                                    $entityInstance = new $entity_class($this->_client, $this->_uri . "/" . $raw_entity->id);
                                    $entityInstance->partialUpdateFromResult($raw_entity);
                                    $entity_list[] = $entityInstance;
                                }

                                return new $collection_type($entity_list);
                            }
                            break;
                    }
                    break;
            }
        }
    }

}
