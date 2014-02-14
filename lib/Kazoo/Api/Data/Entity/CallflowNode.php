<?php

namespace Kazoo\Api\Data\Entity;

use stdClass;

class CallflowNode {

    private $_flow;

    public function __construct() {
        $this->_flow = new stdClass();
        $this->_flow->data = new stdClass();
        $this->_flow->module = '';
        $this->_flow->children = new stdClass();
    }

    public function setModule($module) {
        $this->_flow->module = $module;
    }

    public function setData(stdClass $data) {
        $this->_flow->data = $data;
    }

    public function setDataProperty($property, $value) {
        $this->_flow->data->$property = $value;
    }

    public function addDefaultChild(CallflowNode $node) {
        $this->addChild("_", $node);
        return $node;
    }

    public function addChild($key, CallflowNode $node) {
        $this->_flow->children->$key = $node;
        return $node;
    }

    public function renderFlow() {
        $acc = new stdClass();
        return $this->toFlow($this->_flow, $acc);
    }

    private function toFlow($flow, $acc) {
        $acc->module = $flow->module;
        $acc->data = $flow->data;
        $acc->children = new stdClass();
        foreach ($flow->children as $child_key => $child_callflow_node) {
            $acc->children->$child_key = $child_callflow_node->renderFlow();
        }
        return $acc;
    }

}