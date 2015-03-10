#!/bin/bash

cd `dirname $0`

php ../../../../vendor/phpunit/phpunit/phpunit.php --configuration phpunit.xml $@
