<?php

namespace Kazoo\Api\Exception;

use \Kazoo\HttpClient\Message\Response;

use \Exception;

/**
 * API Exception
 *
 */
class ApiException extends Exception
{
    private $response;

    public function __construct(Response $response) {
        $this->response = $response;

        error_log(print_r($this->response,true));

        parent::__construct($response->getMessage(), $response->getStatusCode());
    }
}
