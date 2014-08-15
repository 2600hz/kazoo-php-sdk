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

        // TODO: log this cleanly...
        var_dump($this->response);

        parent::__construct($response->getMessage(), $response->getStatusCode());
    }
}
