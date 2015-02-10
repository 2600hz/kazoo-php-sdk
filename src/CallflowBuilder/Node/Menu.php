<?php

namespace CallflowBuilder\Node; 

class Menu extends CallflowNodes
{
    public function __construct($id) {
        parent::__construct();
        $this->module = "menu";
        $this->data->id = $id;   
   }   
}
