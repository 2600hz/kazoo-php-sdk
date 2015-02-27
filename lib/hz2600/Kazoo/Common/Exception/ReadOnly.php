<?php

namespace Kazoo\Common\Exception;

use \RuntimeException;

/**
 * ReadOnly Exception
 *
 */
class ReadOnly extends RuntimeException
{
    public function __construct($message){
        parent::__construct($message);
    }
}
