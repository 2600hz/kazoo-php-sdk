<?php

namespace Kazoo\Api;

use Kazoo\Client;
use Kazoo\Api\AbstractApi;

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

}
