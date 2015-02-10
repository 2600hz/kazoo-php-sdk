<?php

namespace CallflowBuilder\Node; 

class Conference extends AbstractNode
{
    public function __construct($id) {
        parent::__construct();
        $this->module = "conference";
        $this->data->id = $id;
    }
}


