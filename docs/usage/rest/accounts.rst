==================
Accounts
==================

Creating a Subaccount
==============================

.. code-block:: php

    $client = new Kazoo\Client($username, $password, $sipRealm);

    $newAccount = $client->api('accounts')->new();
    $newAccount->name = "Test Account";
    $newAccount->realm = "sip.testaccount.com";

    $subaccount = $client->api('accounts')->put($newAccount);
