<?php

/* Install the library via composer or download the .zip file to your project folder. */
/* This line loads the library */
require_once "vendor/autoload.php";

/* Setup your SDK options, most commonly the Kazoo URL. If not provided defaults to localhost */
$options = array('base_url' => 'http://kazoo-crossbar-url:8000');

/* Get an authentication token using ONE of the provided methods */
// $authToken = new Kazoo\AuthToken\None(); /* must have IP auth enabled on Kazoo */
// $authToken = new Kazoo\AuthToken\ApiKey('XXXXX');
$authToken = new Kazoo\AuthToken\User('username', 'password', 'realm');

/* Create a new Kazoo SDK object */
$sdk = new \Kazoo\SDK($authToken, $options);

var_dump("List the siblings of the account used to authenticate");
echo $sdk->Account()->siblings();

var_dump("Get the API key for your account");
echo $sdk->Account()->apiKey();

// var_dump("List the Siblings of a particular account");
// echo $sdk->Account("9142021acb03d27887e47ff3b858c826")->siblings();

/* For convience, create a devices object for the account used to authenticate */
$devices = $sdk->Account()->Devices();

var_dump("List all the devices in account");
echo $devices;

var_dump("List the registration status for the devices");
echo $devices->status();

/* NOTICE: devices refer to a collection and device is an entity... */
/*    That is common to all resources (Users/User, VMBoxes/VMbox, ect) */
// var_dump("Get a specific device");
// echo $sdk->Account()->Device("f5970725ea5907ffd8dd5a2ae9359b65");

var_dump("List the users for the account used to authenticate");
echo $sdk->Account()->Users();

// var_dump("List the users for a specific account");
// echo $sdk->Account()->User("0b9e7613ad0ae3a6393279fec8e28c48");
