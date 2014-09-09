=================
**kazoo-php-sdk**
=================

Status
=======

This documentation is for version 0.1 of `kazoo-php-sdk
<https://www.github.com/2600hz/kazoo-php-sdk>`_.

Quickstart
============
	
Create a Sub-Account
>>>>>>>>>>>>>>>>>>>

The following code will create a new Account resource:

.. code-block:: php

    $options = array("base_url" => "http://kazoo-crossbar-url:8000");
    $authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');
    $client = new \Kazoo\Client($authToken, $options);

    $newAccount = $client->accounts()->new();
    $newAccount->name = "New Test Account";
    $newAccount->realm = "sip".rand(0,10000).".testaccount.com";
    $newAccount->timezone = "America/Chicago";

    $client->accounts()->create($newAccount);

    echo "<pre>";
    echo $account;
    echo "</pre>";

Create a Sub-Sub-Account
>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

The following code will create a new Account resource:

.. code-block:: php

    $options = array("base_url" => "http://kazoo-crossbar-url:8000");
    $authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');
    $client = new \Kazoo\Client($authToken, $options);

    ...
    ...
    $prevAccount = $client->accounts()->retrieve($account_id);
    ...
    ...

    $client->setAccountContext($prevAccount->id);

    $newSubAccount = $client->accounts()->new();
    $newSubAccount->name = "New Sub Test Account";
    $newSubAccount->realm = "sip".rand(0,10000).".subtestaccount.com";
    $newSubAccount->timezone = "America/Chicago";

    $client->accounts()->create($newSubAccount);

    echo "<pre>";
    echo $newSubAccount;
    echo "</pre>";

Create a SIP Device
>>>>>>>>>>>>>>>>>>>>>>

The following code will create a new Device resource for the Account (or sub-account):

.. code-block:: php

    $options = array("base_url" => "http://kazoo-crossbar-url:8000");
    $authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');
    $client = new \Kazoo\Client($authToken, $options);

    $shellDevice = $client->accounts()->devices()->new();
    $num = substr(number_format(time() * rand(),0,'',''),0,4);
    $shellDevice->name = "Test Device #" . $num;
    $shellDevice->sip->password = substr(number_format(time() * rand(),0,'',''),0,10);
    $shellDevice->sip->username = "testdevice".$num;
    $newDevice = $this->client->accounts()->devices()->create($shellDevice);

    echo "<pre>";
    echo $newDevice;
    echo "</pre>";

User / Device / Extension / VoiceMail
>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

The following code with create a User, a device for that User, 

.. code-block:: php

    $start = strtotime('-30 Day') + \Kazoo\Client::GREGORIAN_OFFSET;
    $end = time() + \Kazoo\Client::GREGORIAN_OFFSET;
    $filters = array("created_from" => $start, "created_to" => $end);
    $cdrs = $client->accounts()->cdrs()->retrieve($filters);

    echo "<pre>";
    echo print_r($cdrs);
    echo "</pre>";

Read Account CDRS
>>>>>>>>>>>>>>>>>>>>>>

The following code will generate a list of CDRS

.. code-block:: php

    $start = strtotime('-30 Day') + \Kazoo\Client::GREGORIAN_OFFSET;
    $end = time() + \Kazoo\Client::GREGORIAN_OFFSET;
    $filters = array("created_from" => $start, "created_to" => $end);
    $cdrs = $client->accounts()->cdrs()->retrieve($filters);

    echo "<pre>";
    echo print_r($cdrs);
    echo "</pre>";

Generating Kazoo JSON
>>>>>>>>>>>>>>>>>>>>>>>>

Account JSON:

.. code-block:: php

    $options = array("base_url" => "http://kazoo-crossbar-url:8000");
    $authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');
    $client = new \Kazoo\Client($authToken, $options);
    
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


Installation
============

Features
>>>>>>>>>>>>>

* Follows PSR-0 conventions and coding standard: autoload friendly
* Light and fast thanks to lazy loading of API classes
* Extensively tested and documented

Requirements
>>>>>>>>>>>>>

* PHP >= 5.3.2 with `cURL <http://php.net/manual/en/book.curl.php>`_ extension
* `Guzzle <https://github.com/guzzle/guzzle>`_ library
* `Monolog <https://github.com/Seldaek/monolog>`_ library
* (optional) PHPUnit to run tests.

Autoload
>>>>>>>>>>>>>

The new version of `kazoo-php-sdk` using `Composer <http://getcomposer.org>`_.
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

