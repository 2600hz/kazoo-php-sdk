<?php

namespace Kazoo\Api\Entity;

use \stdClass;

use \Exception;

use \Kazoo\Common\ChainableInterface;
use \Kazoo\Api\Collection\Accounts;

class Account extends AbstractEntity
{
    /**
     *
     *
     */
    public function __construct(ChainableInterface $chain, array $arguments = array()) {
        if (!array_key_exists(0, $arguments)) {
            $arguments[0] = $chain->getSDK()->getAuthToken()->getAccountId();
        }

        parent::__construct($chain, $arguments);
        $this->setTokenValue($this->getEntityIdName(), $this->getId());
    }

    public function children(array $filter = array()) {
        $accounts = new Accounts($this->getChain());
        $accounts->setTokenValue('account_id', $this->getId());
        return $accounts->children($filter);
    }

    public function descendants(array $filter = array()) {
        $accounts = new Accounts($this->getChain());
        $accounts->setTokenValue('account_id', $this->getId());
        return $accounts->descendants($filter);
    }

    public function siblings(array $filter = array()) {
        $accounts = new Accounts($this->getChain());
        $accounts->setTokenValue('account_id', $this->getId());
        return $accounts->siblings($filter);
    }

    // TODO: channels is a read-only property...
    public function channels(array $filter = array()) {
        $accounts = new Accounts($this->getChain());
        $accounts->setTokenValue('account_id', $this->getId());
        return $accounts->channels($filter);
    }

    public function apiKey() {
        $response = $this->get(array(), '/api_key');
        return $response->getData()->api_key;
    }

    public function move() {

    }

    /**
     * Create a child account of this account. Returns the new Account entity.
     *
     * @param \Kazoo\Api\Entity\Account $account The account to create below
     * this account
     *
     * @return \Kazoo\Api\Entity\Account The new child account after saving
     */
    public function createChild($account) {
        $id = $this->getId();
        $payload = $account->getPayload();

        $this->setTokenValue($this->getEntityIdName(), $id);

        $response = $this->put($payload);

        $entity = $response->getData();
        $this->setEntity($entity);

        return $this;
    }
}
