<?php

namespace Kazoo\Exception;

/**
 * ApiLimitExceedException
 *
 */
class ApiLimitExceedException extends RuntimeException {

    public function __construct($limit = 5000, $code = 0, $previous = null) {
        parent::__construct('You have reached Kazoo hour limit! Actual limit is: ' . $limit, $code, $previous);
    }

}
