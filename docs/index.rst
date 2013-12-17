=================
**kazoo-php-sdk**
=================

Status
=======

This documentation is for version 0.1 of `kazoo-php-sdk
<https://www.github.com/2600hz/kazoo-php-sdk>`_.

Quickstart
============

Generating Kazoo JSON
>>>>>>>>>>>>>>>>>>>>>>>>

Account JSON:

.. code-block:: php
    $username = 'testuser';
    $password = 'pAssw0rd';
    $sipRealm = 'sip.realm.com';
    $options  = array();
    $options["base_url"] = "http://127.0.0.1:8000";
    $client = new \Kazoo\Client($username, $password, $sipRealm, $options);

    $account = $client->accounts()->new();
    echo "<pre>";
    echo $account;
    echo "</pre>";

Will result in the following json:

.. code-block:: json 

    {
      "name": "",
      "realm": "",
      "timezone": "",
      "caller_id": {
        "internal": {
          "name": ""
        },
        "external": {
          "name": ""
        },
        "default": {
          "name": ""
        },
        "emergency": {
          "name": ""
        }
      },
      "caller_id_options": {
        "reformat": ""
      },
      "notifications": {
        "voicemail_to_email": {
          "email_text_template": "",
          "email_html_template": "",
          "email_subject_template": "",
          "support_number": "",
          "support_email": "",
          "service_url": "",
          "service_name": "",
          "service_provider": "",
          "send_from": ""
        },
        "deregister": {
          "email_text_template": "",
          "email_html_template": "",
          "email_subject_template": "",
          "support_number": "",
          "support_email": "",
          "service_url": "",
          "service_name": "",
          "service_provider": "",
          "send_from": ""
        },
        "password_recovery": {
          "email_text_template": "",
          "email_html_template": "",
          "email_subject_template": "",
          "support_number": "",
          "support_email": "",
          "service_url": "",
          "service_name": "",
          "service_provider": "",
          "send_from": ""
        },
        "first_occurrence": {
          "send_to": "",
          "sent_initial_registration": false,
          "sent_initial_call": false,
          "email_text_template": "",
          "email_html_template": "",
          "email_subject_template": "",
          "support_number": "",
          "support_email": "",
          "service_url": "",
          "service_name": "",
          "service_provider": "",
          "send_from": ""
        }
      },
      "media": {
        "bypass_media": "",
        "audio": {
          "codecs": []
        },
        "video": {
          "codecs": []
        },
        "fax": {
          "option": ""
        }
      },
      "music_on_hold": {
        "media_id": ""
      }
    }
	
View more examples of JSON generation here: :ref:`usage-json`
	
Create an Account
>>>>>>>>>>>>>>>>>>>

The following code will create a new Account resource:

.. code-block:: php

    $client = new \Kazoo\Client($username, $password, $sipRealm, $options);

    $newAccount = $client->accounts()->new();
    $newAccount->name = "Test Account";
    $newAccount->realm = "sip.testaccount.com";

    $client->accounts()->create($newAccount);

    echo "<pre>";
    echo $account;
    echo "</pre>";

Create a SIP Device
>>>>>>>>>>>>>>>>>>>>>>

The following code will create a new Device resource for the Account (or sub-account):

.. code-block:: php

    $newDevice = $client->accounts()->devices()->new();
    $newDevice->name = "Test Account";
    $newDevice->realm = "sip.testaccount.com";
    $client->accounts()->devices()->create($newDevice);

    echo "<pre>";
    echo $account;
    echo "</pre>";


Installation
============

Features
>>>>>>>>>>>>>

* Follows PSR-0 conventions and coding standard: autoload friendly
* Light and fast thanks to lazy loading of API classes
* Extensively tested and documented

Requirements
>>>>>>>>>>>>>

* PHP >= 5.3.2 with [cURL](http://php.net/manual/en/book.curl.php) extension,
* [Guzzle](https://github.com/guzzle/guzzle) library,
* (optional) PHPUnit to run tests.

Autoload
>>>>>>>>>>>>>

The new version of `kazoo-php-sdk` using [Composer](http://getcomposer.org).
The first step to use `kazoo-php-sdk` is to download composer:

.. code-block:: bash

	$ curl -s http://getcomposer.org/installer | php


Then we have to install our dependencies using:
.. code-block:: bash

	$ php composer.phar install

Now we can use autoloader from Composer by:

.. code-block:: json

	{
	    "require": {
	        "2600hz/kazoo-php-sdk": "*"
	    },
	    "minimum-stability": "dev"
	}


`kazoo-php-sdk` follows the PSR-0 convention names for its classes, which means you can easily integrate `kazoo-php-sdk` classes loading in your own autoloader.


User Guide
==================

REST API
>>>>>>>>>>

.. toctree::
    :maxdepth: 2
    :glob:

    usage/rest
    usage/rest/*

API Documentation
==================

.. toctree::
    :maxdepth: 3
    :glob:

    api/*


Support and Development
===========================

All development occurs on `Github <https://github.com/2600hz/kazoo-php-sdk>`_. To
check out the source, run

.. code-block:: bash

    git clone git@github.com:2600hz/kazoo-php-sdk.git

Report bugs using the Github `issue tracker <https://github.com/2600hz/kazoo-php-sdk/issues>`_.


Running the Tests
>>>>>>>>>>>>>>>>>>>>>>>>>

To run the unit tests

.. code-block:: bash

    phpunit


Making the Documentation
>>>>>>>>>>>>>>>>>>>>>>>>>>

Our documentation is written using `Sphinx <http://sphinx.pocoo.org/>`_. You'll
need to install Sphinx and the Sphinx PHP domain before you can build the docs.

.. code-block:: bash

    make docs-install

Once you have those installed, making the docs is easy.

.. code-block:: bash

    make docs



Indices and tables
==================

* :ref:`genindex`
* :ref:`search`

