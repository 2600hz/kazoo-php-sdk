<?php

namespace Kazoo\Api\CurrentUser;

use Kazoo\Api\AbstractApi;
use Kazoo\Exception\MissingArgumentException;

/**
 *
 */
class CallHistory extends AbstractApi {

    /**
     *
     * @return array
     */
    public function all() {
        return $this->get('user/keys');
    }

    /**
     *
     * @param  string $id
     * @return array
     */
    public function show($id) {
        return $this->get('user/keys/' . rawurlencode($id));
    }

}
