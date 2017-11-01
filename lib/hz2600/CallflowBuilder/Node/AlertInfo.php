<?php

namespace CallflowBuilder\Node;

use \stdClass;

class AlertInfo extends AbstractNode
{
    public function __construct() {
        parent::__construct();
        $this->module = 'alert_info';
    }

    public function alert_info($alert_info){
        $this->data->alert_info = $alert_info;
        return $this;
    }
}
