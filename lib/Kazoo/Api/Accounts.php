<?php

namespace Kazoo\Api;

use Kazoo\Client;
use Kazoo\Api\AbstractApi;
use Kazoo\Api\Accounts\Callflows;
use Kazoo\Api\Accounts\CarrierResources;
use Kazoo\Api\Accounts\Cdrs;
use Kazoo\Api\Accounts\ClickToCalls;
use Kazoo\Api\Accounts\Conferences;
use Kazoo\Api\Accounts\Devices;
use Kazoo\Api\Accounts\Directories;
use Kazoo\Api\Accounts\Faxes;
use Kazoo\Api\Accounts\Groups;
use Kazoo\Api\Accounts\Menus;
use Kazoo\Api\Accounts\PhoneNumbers;
use Kazoo\Api\Accounts\Queues;
use Kazoo\Api\Accounts\Registrations;
use Kazoo\Api\Accounts\TimeOfDayRoutes;
use Kazoo\Api\Accounts\Users;
use Kazoo\Api\Accounts\VoicemailBoxes;
use Kazoo\Api\Accounts\Webhooks;

/**
 * Creating, editing, deleting and listing accounts
 *
 * @link   https://2600hz.atlassian.net/wiki/display/docs/Accounts+API
 */
class Accounts extends AbstractApi {
    
    public function __construct(Client $client) {
        parent::__construct($client);
        $this->setSchemaName("accounts.json");
        $this->setResourceNoun("Account");
    }
    
    public function callflows(){
        return new Callflows($this->client);
    }
    
    public function carrier_resources(){
        return new CarrierResources($this->client);
    }
    
    public function cdrs(){
        return new Cdrs($this->client);
    }
    
    public function click_to_calls(){
        return new ClickToCalls($this->client);
    }
    
    public function conferences(){
        return new Conferences($this->client);
    }
    
    public function devices(){
        return new Devices($this->client);
    }
    
    public function directories(){
        return new Directories($this->client);
    }
    
    public function faxes(){
        return new Faxes($this->client);
    }
    
    public function groups(){
        return new Groups($this->client);
    }
    
    public function menus(){
        return new Menus($this->client);
    }
    
    public function phone_numbers(){
        return new PhoneNumbers($this->client);
    }
    
    public function queues(){
        return new Queues($this->client);
    }
    
    public function registrations(){
        return new Registrations($this->client);
    }
    
    public function time_of_day_routes(){
        return new TimeOfDayRoutes($this->client);
    }
    
    public function users(){
        return new Users($this->client);
    }
    
    public function voicemail_boxes(){
        return new VoicemailBoxes($this->client);
    }
    
    public function webhooks(){
        return new Webhooks($this->client);
    }

}
