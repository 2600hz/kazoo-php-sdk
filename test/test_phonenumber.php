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


/* For the next examples, use a devices variable for convenience */
$phonenumbers = $sdk->Account()->PhoneNumbers();
echo $phonenumbers,"\n";
$find_numbers = $phonenumbers->find('645','15');
echo $find_numbers,"\n";


/* create a new device object from the account */
/*
var_dump("Create a new device");
$device = $sdk->Account()->Device();
$device->name = "Test Device";
$device->save();
echo $device;
*/
/* update the device created above */
/*
var_dump("Update the device");
$device->call_forward->enabled = true;
$device->call_forward->number = ''4158867900;
$device->save();
echo $device();
*/
?>
