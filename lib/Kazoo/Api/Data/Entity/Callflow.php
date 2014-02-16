<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;
use Kazoo\Api\Data\Entity\CallflowNode;

/**
 * Callflow Entity maps to a REST resource.
 * 
 */
class Callflow extends AbstractEntity {

    protected static $_schema_name = "callflows.json";
    protected static $_callflow_module = "callflow";

    public function initDefaultValues() {
        unset($this->featurecode);  //Dont set feature codes by default
    }

    public function getCallflowDefaultData() {
        $this->_default_callflow_data->id = $this->id;
        return $this->_default_callflow_data;
    }

    public function addNumber($number) {
        $numbers = $this->numbers;
        $numbers[] = $number;
        $this->numbers = $numbers;
    }

    public function getNewCallflowNode(AbstractEntity $entity) {
        $node = new CallflowNode();
        $node->setModule($entity->getCallflowModuleName());
        $node->setData($entity->getCallflowDefaultData());
        return $node;
    }

    public function setFlow(CallflowNode $root) {
        $this->flow = $root->renderFlow();
    }

}