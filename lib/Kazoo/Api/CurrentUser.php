<?php

namespace Kazoo\Api;

use Kazoo\Api\CurrentUser\CallHistory;

/**
 *
 */
class CurrentUser extends AbstractApi {

    public function show() {
        return $this->get('user');
    }

    /**
     * @return CallHistory
     */
    public function callhistory() {
        return new CallHistory($this->client);
    }

}
