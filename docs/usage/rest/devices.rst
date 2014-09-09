==================
Devices
==================

Creating a new Device
==============================

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

Get a list of sub accounts
==============================

.. code-block:: php

    $options = array("base_url" => "http://kazoo-crossbar-url:8000");
    $authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');
    $client = new \Kazoo\Client($authToken, $options);

    $devices = $this->client->accounts()->devices()->retrieve();

Get a list of sub accounts
==============================

.. code-block:: php

    $options = array("base_url" => "http://kazoo-crossbar-url:8000");
    $authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');
    $client = new \Kazoo\Client($authToken, $options);

    $accounts = $this->client->accounts()->retrieve();

Get an empty Device
==============================

.. code-block:: php

    $options = array("base_url" => "http://kazoo-crossbar-url:8000");
    $authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');
    $client = new \Kazoo\Client($authToken, $options);

    $device = $this->client->accounts()->devices()->new()
