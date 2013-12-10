.. _ref-rest:

==========================
Using the Kazoo REST API
==========================

Creating a REST Client
=======================

Before querying the API, you'll need to create a :php:class:`Kazoo\Client`
instance. The constructor takes your Kazoo username, password, and sip realm of your root Account.

.. code-block:: php

    $username = "testuser";
    $password = "secret";
	$sipRealm = "test.siprealm.com";
    $client = new Kazoo\Client($username, $password, $sipRealm);