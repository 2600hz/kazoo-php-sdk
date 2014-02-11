<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

/**
 * Account Entity maps to a REST resource.
 * 
 */
class Account extends AbstractEntity {

    protected static $_schema_name = "accounts.json";
    protected static $_callflow_module = "account";

}