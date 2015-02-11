<?php

namespace CallflowBuilder\Node; 


class PlayMedia extends AbstractNode
{
    public function __construct($id) {
        parent::__construct();
        $this->module = "play";
        $this->data->id = $id;
    }
}


