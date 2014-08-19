<?php

namespace Kazoo\Api\Collection;

class Accounts extends AbstractCollection
{
    /**
     *
     *
     */
    public function fetch(array $filter = array()) {
        $this->children($filter);
        return $this;
    }

    /**
     *
     *
     */
    public function children(array $filter = array()) {
        $filter = $this->getFilter($filter);
        $response = $this->get($filter, '/children');
        $this->setCollection($response->getData());
        return $this;
    }

    /**
     *
     *
     */
    public function descendants(array $filter = array()) {
        $filter = $this->getFilter($filter);
        $response = $this->get($filter, '/descendants');
        $this->setCollection($response->getData());
        return $this;
    }

    /**
     *
     *
     */
    public function siblings(array $filter = array()) {
        $filter = $this->getFilter($filter);
        $response = $this->get($filter, '/siblings');
        $this->setCollection($response->getData());
        return $this;
    }

    /**
     *
     *
     */
    protected function getUriSnippet() {
        return '/accounts/{account_id}';
    }
}
