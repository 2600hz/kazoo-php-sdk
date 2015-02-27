<?php

namespace CallflowBuilder\Node; 


class Callflow extends AbstractNode
{
    public function __construct($id) {
        parent::__construct();
        $this->module = "callflow";
        $this->data->id = $id;
    }
}


