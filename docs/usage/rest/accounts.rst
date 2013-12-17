==================
Accounts
==================

Creating a Subaccount
==============================

.. code-block:: php

    $client = new \Kazoo\Client($username, $password, $sipRealm, $options);

    $newAccount = $client->accounts()->new();
    $newAccount->name = "New Test Account";
    $newAccount->realm = "sip".rand(0,10000).".testaccount.com";
    $newAccount->timezone = "America/Chicago";

    $client->accounts()->create($newAccount);

Get a list of sub accounts
==============================

.. code-block:: php

    $client = new \Kazoo\Client($username, $password, $sipRealm, $options);
    $accounts = $this->client->accounts()->retrieve();

Get an empty Account
==============================

.. code-block:: php

    $client = new \Kazoo\Client($username, $password, $sipRealm, $options);
    $account = $this->client->accounts()->new();
