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

/* Get an authentication token */
$authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');

/* Create a new Kazoo SDK object */
$sdk = new \Kazoo\SDK($authToken, $options);

var_dump("List the children of for your account");
echo $sdk->Accounts()->children();

var_dump("Get the API key for your account");
echo $sdk->Account()->apiKey();

var_dump("List the users for your account");
echo $sdk->Account()->Users();

/* For the next examples, use a devices variable for convenience */
$devices = $sdk->Account()->Devices();

var_dump("List all the devices in your account");
echo $devices;

var_dump("List the registration status for the devices");
echo $devices->status();

/* create a new device object from the account */
var_dump("Create a new device");
$device = $sdk->Account()->Device();
$device->name = "Test Device";
$device->save();
echo $device;

/* update the device created above */
var_dump("Update the device");
$device->call_forward->enabled = true;
$device->call_forward->number = '4158867900';
$device->save();
echo $device();

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

/* Get an authentication token */
$authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');

/* Create a new Kazoo SDK object */
$sdk = new \Kazoo\SDK($authToken, $options);

var_dump("List the descendants of the sub-account");
echo $sdk->Accounts($subaccount_id)->descendants();

```

## Filtering lists
```php
<?php

/* Install the library via composer or download the .zip file to your project folder. */
/* This line loads the library */
require_once "vendor/autoload.php";

$subaccount_id = "9142021acb03d27887e47ff3b858c826";
$user_id = "f5970725ea5907ffd8dd5a2ae9359b65";

/* Setup your SDK options, most commonly the Kazoo URL. If not provided defaults to localhost */
$options = array('base_url' => 'http://kazoo-crossbar-url:8000');

/* Get an authentication token */
$authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');

/* Create a new Kazoo SDK object */
$sdk = new \Kazoo\SDK($authToken, $options);

var_dump("List all devices for user $user_id in account $subaccount_id");
$filter = array('filter_owner_id' => $user_id);                                 
$devices = $sdk->Account($subaccount_id)->Devices($filter);
foreach($devices as $element) {
    echo $element;
}
```

## Update all admins
```php
<?php

/* Install the library via composer or download the .zip file to your project folder. */
/* This line loads the library */
require_once "vendor/autoload.php";

/* Setup your SDK options, most commonly the Kazoo URL. If not provided defaults to localhost */
$options = array('base_url' => 'http://kazoo-crossbar-url:8000');

/* Get an authentication token */
$authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');;

$account = $sdk->Account();
$account_id = $account->getId();

var_dump("Set require_password_update for all admins in account $account_id");
$filter = array('filter_priv_level' => 'admin');
foreach($account->Users($filter) as $element) {
    $admin = $element->fetch();
    $admin->require_password_update = true;
    $admin->save();
}

```

##Device Quickcall
```php
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


```

##Phonenumber 
```php

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
$phonenumbers = $sdk->Account()->PhoneNumbers();
echo $phonenumbers,"\n";
$find_numbers = $phonenumbers->find('645','15');
echo $find_numbers,"\n";

?>

```

##CDR
```php


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

$cdrs = $sdk->Account()->Cdrs();
echo $cdrs;
?>

```

##Create Account
```php

function CreateNewAccount($sdk, $account_name) {
	$account = $sdk->Account(null);
	$account->name = $account_name;
	$account->save;
	
	return $account->getId();
}
```
