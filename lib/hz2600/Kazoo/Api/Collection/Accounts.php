<?php

namespace Kazoo\Api\Collection;

//import stdClass to gain access to PUT and POST functions
use \stdClass;

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
     * POST request to create a new icon
     * $type = icon / logo
     */
    public function image($body, $type) {
        // must be an x-base64 content type
        $uri = $this->getURI('/whitelabel/'.$type);
        $response = $this->getSDK()->post($uri, $body, array('Content-Type' => 'application/x-base64'));

        $this->setCollection($response->getData());

        $element_wrapper = $this->getElementWrapper();
        $element_wrapper->unfetchable();

        return $this;
    }

    /**
     * PUT request to create a new whitelabel doc
     *
     */
    public function whitelabelCreate($body) {
        $payload = new stdClass;
        $payload->data = $body;
        $response = $this->put(json_encode($payload), '/whitelabel');
        $this->setCollection($response->getData());

        $element_wrapper = $this->getElementWrapper();
        $element_wrapper->unfetchable();

        return $this;
    }

    /**
     * POST request to update an existing whitelabel doc
     *
     */
    public function whitelabelUpdate($body) {
        $payload = new stdClass;
        //$payload->data = new stdClass;
        $payload->data = $body;
        //$payload->data->quality = $quality;
        $response = $this->post(json_encode($payload), '/whitelabel');
        $this->setCollection($response->getData());

        $element_wrapper = $this->getElementWrapper();
        $element_wrapper->unfetchable();

        return $this;
    }

    /**
     * GET request to retrieve an existing whitelabel doc
     *
     */

    public function whitelabel(array $filter = array()) {
        $filter = $this->getFilter($filter);
        $response = $this->get($filter, '/whitelabel');
        $this->setCollection($response->getData());
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
