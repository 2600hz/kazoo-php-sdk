<?php

namespace Kazoo\Common;

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

class Log
{
    /**
     *
     * @var \Monolog\Logger
     */
    private static $logger;

    private static $options = array();

    public static function getLogger() {
        if (is_null(self::$logger)) {
            self::$logger = new Logger('sdk_logger');
            self::$logger->pushHandler(self::getLoggerHandler());
        }

        return self::$logger;
    }

    public static function addWarning($message) {
        self::getLogger()->addWarning($message);
    }

    public static function addCritical($message) {
        self::getLogger()->addCritical($message());
    }

    private static function getLoggerHandler() {
        switch (self::$options['log_type']) {
        case "file":
            return new StreamHandler(self::$options['log_file'], Logger::DEBUG);
        case "stdout":
            return new StreamHandler('php://stdout', Logger::DEBUG);
        default:
            return new StreamHandler('php://stdout', Logger::CRITICAL);
        }
    }
}
