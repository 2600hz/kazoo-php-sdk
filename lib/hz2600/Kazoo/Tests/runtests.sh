#!/bin/bash

cd `dirname $0`

php ../../../../vendor/phpunit/phpunit/phpunit --configuration phpunit.xml $@
