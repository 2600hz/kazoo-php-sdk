.. _ref-rest:

==========================
Using the Kazoo REST API
==========================

Creating a REST Client
=======================

Before querying the API, you'll need to create a :php:class:`Kazoo\Client`
instance. The constructor takes your Kazoo username, password, and sip realm of your root Account.

.. code-block:: php

    $options = array("base_url" => "http://kazoo-crossbar-url:8000");
    $authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');
    $client = new \Kazoo\Client($authToken, $options);

