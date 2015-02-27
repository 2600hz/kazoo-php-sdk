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

        error_log(print_r($this->response,true));

        parent::__construct($response->getMessage(), $response->getStatusCode());
    }
}
