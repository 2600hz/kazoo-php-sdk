.. _ref-rest:

==========================
Using the Kazoo REST API
==========================

Creating a REST Client
=======================

Before querying the API, you'll need to create a :php:class:`Kazoo\Client`
instance. The constructor takes your Kazoo username, password, and sip realm of your root Account.

.. code-block:: php

    $username = 'testuser';
    $password = 'pAssw0rd';
    $sipRealm = 'sip.realm.com';
    $options  = array();
    $options["base_url"] = "http://127.0.0.1:8000";
    $client = new \Kazoo\Client($username, $password, $sipRealm, $options);