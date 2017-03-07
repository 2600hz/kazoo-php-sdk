<?php
/**
 * Created by PhpStorm.
 * User: rowla
 * Date: 2/12/2017
 * Time: 7:11 PM
 */

namespace CallflowBuilder\Node;


class GroupPickup extends AbstractNode
{
    public function __construct() {
        parent::__construct();
        $this->module = "group_pickup";
    }

    public function numbers($value = array()){
        $this->data->numbers = $value;
        return $this;
    }

    public function contactList($value = array()){
        $this->data->contact_list = $value;
        return $this;
    }
}

