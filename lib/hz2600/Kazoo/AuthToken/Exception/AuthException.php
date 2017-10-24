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
        parent::__construct($response->getMessage(), $response->getStatusCode());
    }

    public function getData() {
        return $this->response->getData();
    }

    public function getStatusCode() {
        return $this->response->getStatusCode();
    }

    public function getResponse() {
        return $this->response;
    }
}
