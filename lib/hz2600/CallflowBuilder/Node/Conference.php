<?php

namespace CallflowBuilder\Node; 

class Conference extends AbstractNode
{
    public function __construct($id = null) {
        parent::__construct();
        $this->module = "conference";
        if ($id) {
            $this->data->id = $id;
        }
    }
}
