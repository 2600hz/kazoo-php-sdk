<?php

namespace CallflowBuilder\Node;

class PrependCid extends AbstractNode
{
    public function __construct() {
        parent::__construct();
        $this->module = 'prepend_cid';
    }

    public function action($action){
        $this->data->action = $action;  
        return $this;
    }

    public function caller_id_name_prefix($prefix){
        $this->data->caller_id_name_prefix = $prefix;  
        return $this;
    }

    public function caller_id_number_prefix($prefix){
        $this->data->caller_id_number_prefix = $prefix;  
        return $this;
    }
}
