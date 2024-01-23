<?php

namespace Kazoo\Common\Exception;

use \RuntimeException;

/**
 * ReadOnly Exception
 *
 */
class ReadOnlyException extends RuntimeException
{
    public function __construct($message){
        parent::__construct($message);
    }
}
