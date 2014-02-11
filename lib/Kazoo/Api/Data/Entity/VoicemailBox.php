<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class VoicemailBox extends AbstractEntity {

    protected static $_schema_name = "vmboxes.json";
    protected static $_callflow_module = "voicemail";

}