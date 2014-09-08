# Authenticating with your API Key

## How to get your API Key
_NOTICE: your API key is very sensitive, take precautions to keep it secure!_

### Via the API
You can get an accounts API key via the API if you first authenticate as a account admin.  This can be achieved via the SDK, and once fetched the api_key can be used for later authentication without needing the admin's credentials.

The following PHP snippet would provide the API key of the account the authenticating user belongs to.  Replace the username, password, and realm with appropriate values for you system.

```php

<?php

require_once "vendor/autoload.php";

$options = array('base_url' => 'http://kazoo-crossbar-url:8000');
$authToken = new Kazoo\AuthToken\User('username', 'password', 'realm');
$client = new \Kazoo\SDK($authToken, $options);

echo $client->Account()->apiKey();

```

### Via the database

If you have access to the BigCouch database UI (Foton) you can get the key from the account document.  Assuming 127.0.0.1 is the IP and 5984 the port of your database, in your webbroswer enter:
```
http://127.0.0.1:5984/_utils/database.html?accounts/_design/accounts/_view/listing_by_name
```

From this page, click the name of the account you wish to get the api_key for.  This should bring up the account document, on which you will find a field "pvt_api_key".


## Creating an AuthToken object
```php
$authToken = new Kazoo\AuthToken\ApiKey('XXXXX');
```
