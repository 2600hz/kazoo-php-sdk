<?php

namespace CallflowBuilder\Node; 


class PlayMedia extends CallflowNodes
{
    public function __construct($id) {
        parent::__construct();
        $this->module = "play";
        $this->data->id = $id;
    }
}


