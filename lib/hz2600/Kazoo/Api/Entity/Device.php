<?php

namespace Kazoo\Api\Entity;

class Device extends AbstractEntity
{
    /**
     * Perform an action on this device
     *
     * @param string $action The name of the action to perform
     * @param stdClass|null $data Optional extra data to control how the action
     * is performed
     */
    public function performAction($action, $data = null) {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());

        $payload = new \stdClass;
        $payload->data = new \stdClass;
        $payload->data->action = $action;
        $payload->data->data = is_null($data) ? new \stdClass : $data;

        $this->put(json_encode($payload));
    }

    public function quickcall($number, $options = array()) {
        $url = '/quickcall/{quickcall_number}';
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        $this->setTokenValue('quickcall_number', $number);

        return $this->get($options, $url);
    }

    public function sync() {
        $url = '/sync';
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        return $this->post(array(), $url);
    }
}
