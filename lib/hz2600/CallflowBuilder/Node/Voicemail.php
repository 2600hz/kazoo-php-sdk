<?php

namespace CallflowBuilder\Node;

class Voicemail extends AbstractNode
{
    public function __construct($id) {
        parent::__construct();
        $this->module = "vm_box";
        $this->data->id = $id;
    }

    public function action($action = "compose") {
        $this->data->action = $action;
        return $this;
    }

}

