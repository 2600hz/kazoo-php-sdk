<?php

namespace Kazoo\HttpClient\Message;

use \stdClass;

use \GuzzleHttp\Psr7\Response as GuzzleResponse;

class Response
{
    /**
     *
     * @var \GuzzleHttp\Psr7\Response
     */
    private $response;

    /**
     *
     * @param \GuzzleHttp\Psr7\Response $response
     */
    public function __construct(GuzzleResponse $response) {
        $this->response = $response;
    }

    /**
     *
     * @return stdClass
     */
    public function getData() {
        return $this->getJson()->data;
    }

    /**
     *
     * @return null | string
     */
    public function getAuthToken() {
        return $this->getJson()->auth_token;
    }

    /**
     *
     * @return string
     */
    public function getStatus() {
        return $this->getJson()->status;
    }

    /**
     *
     * @return null | string
     */
    public function getMessage() {
        return $this->getJson()->message;
    }

    /**
     *
     * @return null | string
     */
    public function getError() {
        return $this->getJson()->error;
    }

    /**
     *
     * @return null | string
     */
    public function getRequestId() {
        return $this->response->getHeader('X-Request-ID');
    }

    /**
     *
     * @return string
     */
    public function getBody() {
        return $this->response->getBody();
    }

    /**
     *
     * @return string
     */
    public function getHeaders() {
        return $this->response->getHeaders();
    }

    /**
     *
     * @return string
     */
    public function getHeader($header) {
        return $this->response->getHeader($header);
    }

    /**
     *
     * @return int
     */
    public function getStatusCode() {
        return $this->response->getStatusCode();
    }

    /**
     *
     * @return string
     */
    public function getReasonPhrase() {
        return $this->response->getReasonPhrase();
    }

    /**
     *
     * @return stdClass
     */
    public function getJson() {
        $body = $this->getBody();
        return json_decode($body);
    }

    /**
     *
     * @return \GuzzleHttp\Psr7\Response
     */
    public function getResponse() {
        return $this->response;
    }
}
