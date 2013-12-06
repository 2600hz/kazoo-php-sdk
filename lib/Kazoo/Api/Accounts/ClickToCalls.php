<?php

namespace Kazoo\Api\Accounts;
use Kazoo\Client;
use Kazoo\Api\AbstractApi;

/**
 * Creating, editing, deleting and listing devices
 *
 * @link   https://2600hz.atlassian.net/wiki/display/docs/Accounts+API
 */
class ClickToCalls extends AbstractApi {
    
    public function __construct(Client $client) {
        parent::__construct($client);
        $this->setSchemaName("clicktocall.json");
        $this->setResourceNoun("ClickToCall");
    }
}
