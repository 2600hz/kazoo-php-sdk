==================
Accounts
==================

Creating a Subaccount
==============================

.. code-block:: php

    $options = array("base_url" => "http://kazoo-crossbar-url:8000");
    $authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');
    $client = new \Kazoo\Client($authToken, $options);

    $newAccount = $client->accounts()->new();
    $newAccount->name = "New Test Account";
    $newAccount->realm = "sip".rand(0,10000).".testaccount.com";
    $newAccount->timezone = "America/Chicago";

    $client->accounts()->create($newAccount);

Get a list of sub accounts
==============================

.. code-block:: php

    $options = array("base_url" => "http://kazoo-crossbar-url:8000");
    $authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');
    $client = new \Kazoo\Client($authToken, $options);

    $accounts = $this->client->accounts()->retrieve();

Get an empty Account
==============================

.. code-block:: php

    $options = array("base_url" => "http://kazoo-crossbar-url:8000");
    $authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');
    $client = new \Kazoo\Client($authToken, $options);

    $account = $this->client->accounts()->new();
