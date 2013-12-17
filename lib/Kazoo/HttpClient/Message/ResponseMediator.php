<?php

namespace Kazoo\HttpClient\Message;

use Guzzle\Http\Message\Response;
use Kazoo\Exception\ApiLimitExceedException;

class ResponseMediator {

    public static function getContent(Response $response, $json_force_object = false) {
        $body = $response->getBody(true);
        $content = json_decode($body, $json_force_object);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return $body;
        }

        return $content;
    }

    public static function getPagination(Response $response) {
        $header = $response->getHeader('Link');

        if (empty($header)) {
            return null;
        }

        $pagination = array();
        foreach (explode(',', $header) as $link) {
            preg_match('/<(.*)>; rel="(.*)"/i', trim($link, ','), $match);

            if (3 === count($match)) {
                $pagination[$match[2]] = $match[1];
            }
        }

        return $pagination;
    }

    public static function getApiLimit(Response $response) {
        $remainingCalls = $response->getHeader('X-RateLimit-Remaining');

        if (null !== $remainingCalls && 1 > $remainingCalls) {
            throw new ApiLimitExceedException($remainingCalls);
        }
    }

}
