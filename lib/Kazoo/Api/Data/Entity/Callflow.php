<?php

namespace Kazoo\Api\Data\Entity;
use Kazoo\Api\Data\AbstractEntity;

/**
 * Callflow Entity maps to a REST resource.
 * 
 */
class Callflow extends AbstractEntity {
    protected static $_schema_name = "callflows.json";
    protected static $_callflow_module = "callflow";
}