<?php

namespace Kazoo\Api\Entity;

use \Kazoo\Api\Collection\Devices;

class User extends AbstractEntity
{
    /**
     * Get a collection of CDRs owned by this user. This API comes from cb_cdrs.
     *
     * @param array $filter Key-value pairs to filter for in the returned
     * collection.
     *
     * @param \Kazoo\Api\Collection\Cdrs The filtered collection of CDRs for
     * this user.
     */
    public function cdrs(array $filter = array()) {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());

        $collection_name = '\\Kazoo\\Api\\Collection\\Cdrs';
        return new $collection_name($this, array($filter));
    }

    /**
     * Get a collection of channels that are active for this user.
     *
     * @param array $filter Key-value pairs to filter for in the returned
     * collection.
     *
     * @return \Kazoo\Api\Collection\Channels The filtered collection of
     * channels for this user.
     */
    public function channels(array $filter = array()) {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());

        $collection_name = '\\Kazoo\\Api\\Collection\\Channels';
        return new $collection_name($this, array($filter));
    }

    public function devices(array $filter = array()) {
        $this->setTokenValue($this->getEntityIdName(), $this->getId());

        $collection_name = '\\Kazoo\\Api\\Collection\\Devices';
        return new $collection_name($this, array($filter));
    }

    public function quickcall($number, $options = array()) {
        $url = '/quickcall/{quickcall_number}';
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
        $this->setTokenValue('quickcall_number', $number);
        $this->get($options, $url);
    }
}
