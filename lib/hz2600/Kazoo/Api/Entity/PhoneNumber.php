<?php
namespace Kazoo\Api\Entity;

use \stdClass;

use \kazoo\HttpClient\Exception\HttpException;

use \Kazoo\Common\Exception\ReadOnly;

class PhoneNumber extends AbstractEntity
{
    private $new_number = TRUE;

    /**
     *
     *
     */
    public function getId() {
        $id = parent::getId();
        return urlencode($id);
    }

    public function notNew() {
        $this->new_number = FALSE;
    }

    /**
     *
     *
     */
    public function activate() {
        $id = $this->getId();
        $this->setTokenValue($this->getEntityIdName(), $id);
        $this->put('', "/activate");
    }

    /**
     *
     *
     */
    public function reserve() {
        $id = $this->getId();
        $this->setTokenValue($this->getEntityIdName(), $id);
        $this->put('', "/reserve");
    }

    /**
     *
     *
     */
    public function port() {
        return $this->save('/port');
    }

    /**
     *
     *
     */
    public function identify() {
        $this->fetch('/identify');
        $this->readOnly();
        return $this;
    }

    /**
     *
     *
     */
    public function fetch($append_uri = null) {
        $id = $this->getId();

        try {
            parent::fetch($append_uri);
        } catch (HttpException $e) {
            $this->setEntity(new stdClass);
            $this->new_number = TRUE;
        }
        $this->setId($id);

        return $this;
    }

    /**
     *
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
