<?php

namespace CallflowBuilder\Node; 

class Menu extends AbstractNode
{
    public function __construct($id) {
        parent::__construct();
        $this->module = "menu";
        $this->data->id = $id;   
   }   
}
