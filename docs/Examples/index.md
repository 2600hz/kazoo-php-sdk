# Example

## Basics

Without specifying an account id as an argument to the Account object, it will default to the account associated with the AuthToken.

```php
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

var_dump("List the children of for your account");
echo $sdk->Account()->children();

var_dump("Get the API key for your account");
echo $sdk->Account()->apiKey();

/* For the next examples create a devices object */
$devices = $sdk->Account()->Devices();

var_dump("List all the devices in your account");
echo $devices;

var_dump("List the registration status for the devices");
echo $devices->status();

var_dump("List the users for your account");
echo $sdk->Account()->Users();

// var_dump("List the users for a specific account");
// echo $sdk->Account()->User("0b9e7613ad0ae3a6393279fec8e28c48");

```

## Working in different sub-accounts
```php
<?php

/* Install the library via composer or download the .zip file to your project folder. */
/* This line loads the library */
require_once "vendor/autoload.php";

$subaccount_id = "9142021acb03d27887e47ff3b858c826";

/* Setup your SDK options, most commonly the Kazoo URL. If not provided defaults to localhost */
$options = array('base_url' => 'http://kazoo-crossbar-url:8000');

/* Get an authentication token using ONE of the provided methods */
// $authToken = new Kazoo\AuthToken\None(); /* must have IP auth enabled on Kazoo */
// $authToken = new Kazoo\AuthToken\ApiKey('XXXXX');
$authToken = new Kazoo\AuthToken\User('username', 'password', 'realm');

/* Create a new Kazoo SDK object */
$sdk = new \Kazoo\SDK($authToken, $options);

var_dump("List the siblings of the sub-account");
echo $sdk->Account($subaccount_id)->siblings();

```

## Working with a user in a sub-account
```php
<?php

/* Install the library via composer or download the .zip file to your project folder. */
/* This line loads the library */
require_once "vendor/autoload.php";

$subaccount_id = "9142021acb03d27887e47ff3b858c826";
$user_id = "f5970725ea5907ffd8dd5a2ae9359b65";

/* Setup your SDK options, most commonly the Kazoo URL. If not provided defaults to localhost */
$options = array('base_url' => 'http://kazoo-crossbar-url:8000');

/* Get an authentication token using ONE of the provided methods */
// $authToken = new Kazoo\AuthToken\None(); /* must have IP auth enabled on Kazoo */
// $authToken = new Kazoo\AuthToken\ApiKey('XXXXX');
$authToken = new Kazoo\AuthToken\User('username', 'password', 'realm');

/* Create a new Kazoo SDK object */
$sdk = new \Kazoo\SDK($authToken, $options);

/* NOTICE: devices refer to a collection and device is an entity... */
/*    That is common to all resources (Users/User, VMBoxes/VMbox, ect) */
var_dump("Forcing user $user in account $subaccount_id to reset their password on next login...");
$user = $sdk->Account($subaccount_id)->User($user_id);
$user->require_password_update = true;
$user->save();

echo $user;

```
