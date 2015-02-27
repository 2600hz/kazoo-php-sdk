<?php

namespace CallflowBuilder\Node;

class Voicemail extends AbstractNode
{
    public function __construct($id = null) {
        parent::__construct();
        $this->module = "voicemail";
        if (isset($id)){
            $this->id($id);
        } 
    }

    public function id($id){
        $this->data->id = $id; 
        return $this; 
    }

    public function action($action = "compose") {
        $this->data->action = $action;
        return $this;
    }

}

