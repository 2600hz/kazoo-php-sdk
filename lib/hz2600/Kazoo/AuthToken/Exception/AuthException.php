<?php

namespace Kazoo\AuthToken\Exception;

use \Exception;
use \Kazoo\HttpClient\Message\Response;

/**
 * Auth Exception
 *
 */
class AuthException extends Exception
{
    private $response;

    public function __construct(Response $response) {
        $this->response = $response;

        error_log(print_r($this->response,true));

        parent::__construct($response->getMessage(), $response->getStatusCode());
    }
}
