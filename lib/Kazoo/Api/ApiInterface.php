<?php

namespace Kazoo\Api;

use Kazoo\Client;

/**
 * Api interface
 *
 */
interface ApiInterface {

    public function __construct(Client $client);

    public function getPerPage();

    public function setPerPage($perPage);
}
