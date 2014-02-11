<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class User extends AbstractEntity {

    protected static $_schema_name = "users.json";
    protected static $_callflow_module = "user";
}