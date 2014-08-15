<?php

namespace Kazoo\HttpClient\Exception;

use \Kazoo\HttpClient\Message\Response;

/**
 * Http Client Exception
 *
 */
class HttpException extends \Exception {

    private $response;

    public function __construct(Response $response) {
        $this->response = $response;

        // TODO: log this cleanly...
        var_dump($this->response);

        parent::__construct($response->getMessage(), $response->getStatusCode());
    }
}
