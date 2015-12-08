<?php

/* Install the library via composer or download the .zip file to your project folder. */
/* This line loads the library */
require_once "vendor/autoload.php";

$device_id = '5a151492a3d46a34d0b8992f3647113a';


/* Setup your SDK options, most commonly the Kazoo URL. If not provided defaults to localhost */
$options = array('base_url' => 'http://192.168.181.25:8000');

/* Get an authentication token */
$authToken = new \Kazoo\AuthToken\User('admin', 'password', 'sip.realm.com');
/* Create a new Kazoo SDK object */
$sdk = new \Kazoo\SDK($authToken, $options);


/* setup options of the caller, use device function quickcall*/
$options_caller = array('cid-number' => "4001");
$device = $sdk->Account()->Device($device_id);
$device->quickcall(4002,$options_caller);


?>
