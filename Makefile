# Kazoo API helper library.
# See LICENSE file for copyright and license details.

define LICENSE
<?php

/**
 * Kazoo API helper library.
 *
 * @package   Kazoo
 * @author    Ben Wann <ben@2600hz.com>
 * @license   http://creativecommons.org/licenses/MIT/ MIT
 * @link      http://github.com/2600hz/kazoo-php-sdk
 */
endef
export LICENSE

all: test

clean:
	@rm -rf dist venv

PHP_FILES = `find dist -name \*.php`
dist: clean
	@mkdir dist
	@git archive master | (cd dist; tar xf -)
	@for php in $(PHP_FILES); do\
	  echo "$$LICENSE" > $$php.new; \
	  tail -n+2 $$php >> $$php.new; \
	  mv $$php.new $$php; \
	done

test-install:
	# Composer: http://getcomposer.org/download/
	composer install

# if these fail, you may need to install the helper library - run "make
# test-install"
test:
	@PATH=vendor/bin:$(PATH) phpunit --strict --colors --configuration tests/phpunit.xml;

venv:
	virtualenv venv

docs-install: venv
	. venv/bin/activate; pip install -r docs/requirements.txt

docs:
	. venv/bin/activate; cd docs && make html

authors:
    echo "Authors\n=======\n\nMany thanks to all of our contributors:\n\n" > AUTHORS.md
    git log --format='%aN' | awk '{arr[$0]++} END{for (i in arr){print arr[i], i;}}' | sort -rn | cut -d " " -f2- | sed 's/^/- /' >> AUTHORS.md

.PHONY: all clean dist test docs docs-install test-install authors
