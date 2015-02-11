<?php

namespace Kazoo\HttpClient\Listener;

use \Kazoo\HttpClient\Message\Response;

use \Kazoo\Api\Exception\ApiException;
use \Kazoo\Api\Exception\Validation;
use \Kazoo\Api\Exception\RateLimit;
use \Kazoo\Api\Exception\Billing;
use \Kazoo\Api\Exception\Conflict;

use \Kazoo\AuthToken\Exception\Unauthenticated;
use \Kazoo\AuthToken\Exception\Unauthorized;

use \Kazoo\HttpClient\Exception\HttpException;
use \Kazoo\HttpClient\Exception\NotFound;
use \Kazoo\HttpClient\Exception\InvalidMethod;

use \Guzzle\Common\Event;

/**
 *
 */
class ErrorListener
{
    /**
     *
     * @param \Guzzle\Common\Event $event
     */
    public function onRequestError(Event $event) {
        $request = $event['request'];
        $response = new Response($request->getResponse());
        $code = $response->getStatusCode();

        switch ($code) {
        case 400:
            throw new Validation($response);
        case 401:
            // invalid creds
            throw new Unauthenticated($response);
        case 402:
            // not enough credit
            throw new Billing($response);
        case 403:
            // forbidden
            throw new Unauthorized($response);
        case 404:
            // not found
            throw new NotFound($response);
        case 405:
            // invalid method
            throw new InvalidMethod($response);
        case 409:
            // conflicting documents
            throw new Conflict($response);
        case 429:
            // too many requests
            throw new RateLimit($response);
        default:
            if ($code >= 400 && $code < 500) {
                throw new ApiException($response);
            } else if ($code > 500) {
                throw new HttpException($response);
            }
        }
    }
}