<?php

namespace CallflowBuilder\Node; 

class ReceiveFax extends AbstractNode
{
    public function __construct($id) {
        parent::__construct();
        $this->module = "receive_fax";
        $this->data->owner_id = $id;
    }   

    public function fax_option($value = FALSE) {
        $this->data->media->fax_option = $value;
        return $this;
    }
}
