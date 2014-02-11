<?php

namespace Kazoo\Api\Data\Entity;
use Kazoo\Api\Data\AbstractEntity;

class TimedBasedRoute extends AbstractEntity {
    temporal_routes
    protected static $_schema_name = "temporal_routes.json";
    protected static $_callflow_module = "temporal_route";
}