<?php

require_once "lib/Kazoo/SDK.php";

$authToken = new Kazoo\AuthToken\User('username', 'password', 'realm');
$client = new \Kazoo\SDK($authToken);

var_dump($client->Account()->Devices()->fetch());
var_dump($client->Account()->Device("f5970725ea5907ffd8dd5a2ae9359b65")->fetch());
var_dump($client->Account()->siblings());

var_dump($client->Account("1760753c8d022d650418fbbe6a1a10e0")->Users()->fetch());
var_dump($client->Account("1760753c8d022d650418fbbe6a1a10e0")->User("0b9e7613ad0ae3a6393279fec8e28c48")->fetch());