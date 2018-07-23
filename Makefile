ROOT = $(shell readlink -f .)

.PHONY = fmt fmt-project set-defaults \
	docs docs-validate docs-report docs-setup docs-build docs-clean

FORMATTER ?= ./vendor/bin/phpcbf
CHECKER ?= ./vendor/bin/phpcs

JS := $(wildcard *.json)

CHANGED ?= $(shell git --no-pager diff --name-only HEAD origin/master -- *.php)
PHP ?= $(shell find lib -name *.php)

fmt: $(FORMATTER) $(JS)
	@$(if $(CHANGED),$(FORMATTER) $(CHANGED))

fmt-project: $(FORMATTER)
	@$(FORMATTER) $(PHP)

%.js:
	@$(ROOT)/scripts/format-js.py $@

$(FORMATTER):
	@$(ROOT)/composer update
	@$(MAKE) set-defaults

set-defaults:
	@$(CHECKER) --config-set default_standard PSR1
	@$(CHECKER) --config-set report_format summary
	@$(CHECKER) --config-set colors 1
	@$(CHECKER) --config-set tab_width 4

DOCS_ROOT=$(ROOT)/docs/mkdocs
docs: docs-validate docs-report docs-setup docs-build

docs-validate:

docs-report:
	$(ROOT)/scripts/reconcile_docs_to_index.bash

docs-setup:
	$(ROOT)/scripts/validate_mkdocs.py
	$(ROOT)/scripts/setup_docs.bash
	mkdir -p $(DOCS_ROOT)/theme $(DOCS_ROOT)/docs $(DOCS_ROOT)/site

docs-build:
	$(MAKE) -C $(DOCS_ROOT) DOCS_ROOT=$(DOCS_ROOT) docs-build

docs-clean:
	$(MAKE) -C $(DOCS_ROOT) DOCS_ROOT=$(DOCS_ROOT) clean

docs-serve: docs-setup docs-build
	$(MAKE) -C $(DOCS_ROOT) DOCS_ROOT=$(DOCS_ROOT) docs-serve
