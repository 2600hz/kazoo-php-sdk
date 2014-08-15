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

        // TODO: log this cleanly...
        var_dump($this->response);

        parent::__construct($response->getMessage(), $response->getStatusCode());
    }
}