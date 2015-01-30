<?php
namespace Kazoo\Api\Entity;

use \stdClass;

use \Guzzle\Http\Exception\ServerErrorResponseException;

use \Kazoo\Common\Exception\ReadOnly;

class PhoneNumber extends AbstractEntity
{
    private $new_number = FALSE;

    public function getId() {
        $id = parent::getId();
        return urlencode($id);
    }

    public function activate() {
        $id = $this->getId();
        $this->setTokenValue($this->getEntityIdName(), $id);
        $this->put(array(), "/activate");
    }

    public function reserve() {
        $id = $this->getId();
        $this->setTokenValue($this->getEntityIdName(), $id);
        $this->put(array(), "/reserve");
    }

    public function port() {
        return $this->save('/port');
    }

    public function identify() {
        $this->fetch('/identify');
        $this->readOnly();
        return $this;
    }

    /**
     * Explicitly fetch from Kazoo, typicall it lazy-loads.
     * This could also be used to re-load to ensure the data
     * is fresh.
     *
     */
    public function fetch($append_uri = null) {
        $id = $this->getId();
        $this->setTokenValue($this->getEntityIdName(), $id);

        try {
            $response = $this->get(array(), $append_uri);
            $entity = $response->getData();
        } catch (ServerErrorResponseException $e) {
            $entity = new stdClass;
            $this->new_number = TRUE;
        }

        $this->setEntity($entity);
        $this->setId($id);

        return $this;
    }

    /**
     * Saves the current entity, if it does not have an
     * id then it will be created.
     *
     */
    public function save($append_uri = null) {
        if ($this->read_only) {
            throw new ReadOnly("The entity is read-only");
        }

        $id = $this->getId();
        $payload = $this->getPayload();
        $this->setTokenValue($this->getEntityIdName(), $id);

        if ($this->new_number) {
            $response = $this->put($payload, $append_uri);
            $this->new_number = FALSE;
        } else {
            $response = $this->post($payload, $append_uri);
        }

        $entity = $response->getData();
        $this->setEntity($entity);

        return $this;
    }
}
