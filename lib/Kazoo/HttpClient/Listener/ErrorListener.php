<?php

namespace Kazoo\HttpClient\Listener;

use Kazoo\HttpClient\Message\ResponseMediator;
use Guzzle\Common\Event;
use Guzzle\Http\Message\Response;

use Kazoo\Exception\ApiLimitExceedException;
use Kazoo\Exception\ErrorException;
use Kazoo\Exception\RuntimeException;
use Kazoo\Exception\ValidationFailedException;
use Kazoo\Exception\AuthenticationException;

/**
 *
 */
class ErrorListener
{
    /**
     * @var array
     */
    private $options;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritDoc}
     */
    public function onRequestError(Event $event)
    {
        /** @var $request \Guzzle\Http\Message\Request */
        $request = $event['request'];
        $response = $request->getResponse();

        if ($response->isClientError() || $response->isServerError()) {
            $remaining = (string) $response->getHeader('X-RateLimit-Remaining');

            if (null != $remaining && 1 > $remaining && 'rate_limit' !== substr($request->getResource(), 1, 10)) {
                throw new ApiLimitExceedException($this->options['api_limit']);
            }

            $content = ResponseMediator::getContent($response, true);
            if (!is_array($content) || !isset($content['message'])) {
                $content = array(
                    'message' => 'unknown error',
                    'errors' => array()
                );
            }

            switch ($response->getStatusCode()) {
            case 400:
                throw new ErrorException($content['message'], 400);
            case 401:
                $message = $response->getStatusCode() . " " . $response->getReasonPhrase() . " " . $response->getProtocol() . $response->getProtocolVersion();
                throw new AuthenticationException($message);
            default:
                $this->collectValidationErrors($content);
            }
        }

        throw new RuntimeException(isset($content['message']) ? $content['message'] : $content, $response->getStatusCode());
    }

    private function collectValidationErrors($content) {
        $errors = array();
        foreach ($content['errors'] as $error) {
            switch ($error['code']) {
            case 'missing':
                $errors[] = sprintf('The %s %s does not exist, for resource "%s"', $error['field'], $error['value'], $error['resource']);
                break;

            case 'missing_field':
                $errors[] = sprintf('Field "%s" is missing, for resource "%s"', $error['field'], $error['resource']);
                break;

            case 'invalid':
                $errors[] = sprintf('Field "%s" is invalid, for resource "%s"', $error['field'], $error['resource']);
                break;

            case 'already_exists':
                $errors[] = sprintf('Field "%s" already exists, for resource "%s"', $error['field'], $error['resource']);
                break;

            default:
                $errors[] = $error['message'];
                break;

            }
        }

        throw new ValidationFailedException('Validation Failed: ' . implode(', ', $errors), 422);
    }
}