<?php

namespace CallflowBuilder\Node; 

class Conference extends CallflowNodes
{
    public function __construct($id) {
        parent::__construct();
        $this->module = "conference";
        $this->data->id = $id;
    }
}


