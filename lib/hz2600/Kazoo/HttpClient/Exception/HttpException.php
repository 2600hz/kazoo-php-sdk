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
