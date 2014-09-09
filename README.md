# PHP Kazoo API

A simple Object Oriented wrapper for Kazoo API, written with PHP5.

## Features

* Follows PSR-0 conventions and coding standard: autoload friendly
* Light and fast thanks to lazy loading of API classes
* Extensively tested and documented

## Requirements

* PHP >= 5.3.2 with [cURL](http://php.net/manual/en/book.curl.php) extension,
* [Guzzle](https://github.com/guzzle/guzzle) library,
* (optional) PHPUnit to run tests.

## Autoload

The new version of `kazoo-php-sdk` using [Composer](http://getcomposer.org).
The first step to use `kazoo-php-sdk` is to download composer:

```bash
$ curl -s http://getcomposer.org/installer | php
```

Then we have to install our dependencies using:
```bash
$ php composer.phar install
```
Now we can use autoloader from Composer by:

```yaml
{
    "require": {
        "2600hz/kazoo-php-sdk": "*"
    },
    "minimum-stability": "dev"
}
```

> `kazoo-php-sdk` follows the PSR-0 convention names for its classes, which means you can easily integrate `kazoo-php-sdk` classes loading in your own autoloader.

## Basic usage of `kazoo-php-sdk` client

```php
<?php

// Install the library via composer or download the .zip file to your project folder.
// This line loads the library
require_once "kazoo-php-sdk/lib/Kazoo/Client.php";

$options = array("base_url" => "http://kazoo-crossbar-url:8000");
$authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');
$client = new \Kazoo\Client($authToken, $options);

$devices = $client->accounts()->devices()->retrieve();
foreach($devices as $device){
	echo $device->toJSON();	//Your device configurations for the logged in account
}
```

From `$client` object, you can access to all Kazoo.

## Documentation

See the `doc` directory for more detailed documentation.

## License

`kazoo-php-sdk` is licensed under the MIT License - see the LICENSE file for details

## Credits

### Sponsored by

[![2600hz](http://2600hz.com/images/logo.png)](http://2600hz.com)

### Contributors
- [Ben Wann](https://github.com/tickbw)
- [Karl Anderson](https://github.com/k-anderson)
- [James Aimonetti](https://github.com/jamesaimonetti)
- [Darren Schreiber](https://github.com/dschreiber)
- [Peter Defebvre](https://github.com/macpie)
- [Francis Genet](https://github.com/frifri)
- Thanks to [KnpLabs/php-github-api](https://github.com/KnpLabs/php-github-api) for their work on the Php github api, which inspired this library

Thanks to GitHub for the high quality API and documentation.
