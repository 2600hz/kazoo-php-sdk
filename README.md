# PHP Kazoo API

A simple Object Oriented wrapper for Kazoo API, written with PHP5.

## _NOTICE:_ Master is no longer backward compatibile with version 1.x

The master brach of this repo represents a new approach to the SDK, which will become version 2.x.

If you are currently using the SDK the 1.x branches will be maintained until otherwise notified.

## Features

* Follows PSR-0 conventions and coding standard: autoload friendly
* Light and fast thanks to lazy loading of API classes
* Extensively tested and documented

## Requirements

* PHP >= 5.3.2
  * [php-xml](http://php.net/manual/en/refs.xml.php) extension
  * [cURL](http://php.net/manual/en/book.curl.php) extension

Dependencies for the SDK are managed using [Composer](http://getcomposer.org).  For your convience we have included the composer binary in the root directory of the project.

However, if you would like to install Composer to your local system you can run (optional):
```bash
$ curl -s http://getcomposer.org/installer | php
```

## Installing the SDK
_NOTICE: These instructions are a work-in-progress. Do you have a better way? Let us know!_

* Browse to the GitHub repo for the [Kazoo SDK](https://github.com/2600hz/kazoo-php-sdk) and select the branch which represents the version you wish to use.
* Click "Download ZIP"
* Unzip the SDK into your project
* The SDK follows the PSR-0 convention names for its classes, which means you should be able to easily integrate `kazoo-php-sdk` class loading in your own autoloader.

## Installing the SDK (with Composer)

_This assumes basic familiarization with composer.  If you have not used Composer before you might read the [getting started guide](https://getcomposer.org/doc/00-intro.md)._

Add the following require line:

```yaml
"2600hz/kazoo-php-sdk": "dev-master"
```

Using Composer update or install your project dependencies.

If your project is already including the auto-generated autloader, then you are done!

## Example Usage

In this example we will find all admins of the account belonging to the authenticated user.  It will then set "require_password_update" to `true` for each admin and save it back to the db.


```php
<?php

/* Install the library via composer or download the .zip file to your project folder. */
/* This line loads the library */
require_once "vendor/autoload.php";

/* Setup your SDK options, most commonly the Kazoo URL. If not provided defaults to localhost */
$options = array('base_url' => 'http://kazoo-crossbar-url:8000');

/* Get an authentication token using ONE of the provided methods */
// $authToken = new \Kazoo\AuthToken\None(); /* must have IP auth enabled on Kazoo */
// $authToken = new \Kazoo\AuthToken\ApiKey('XXXXX');
$authToken = new \Kazoo\AuthToken\User('username', 'password', 'sip.realm');

$sdk = new \Kazoo\SDK($authToken, $options);

$filter = array('filter_priv_level' => 'admin');
$users = $sdk->Account()->Users($filter);
foreach ($users as $element) {
    $admin = $element->fetch();
    $admin->require_password_update = true;
    $admin->save();
}

```

This is a basic example, from `$sdk` object you can access the full power of Kazoo!

# Check out the [examples](docs/Examples/index.md) for more!
#
# To see the lastest Unit testing and great examples of functions that were tested, see:
# /kazoo-php-sdk/lib/hz2600/Kazoo/Tests/Functional

## We need your help with version 2.x
* Version 2.x of the SDK needs to have the Entity and Collection classes for each Kazoo API built, with unit tests
* We need to create documentation
* We need testers!

Interested?  Heres how to get started!
* Install your favorite webserver with PHP 5.3.2+
* Clone the SDK from your fork to your webserver directory, in this example we will use:
```bash
$ cd /var/www/html/
$ git clone git@github.com:{YOUR_GITHUB_ORGANIZATION}/kazoo-php-sdk.git
```
* Create a new branch for your changes
```base
$ git branch -b MY-FEATURE-BRANCH
```
* Make changes!
* Commit the changes
```bash
$ git add .
$ git commit -m 'added the XXX API'
$ git push origin MY-FEATURE-BRANCH
```
* Login to GitHub, and your should have a "Compare & pull request" button on fork.  Follow the instructions provided by that tool!

## Documentation

See the `doc` directory for more detailed documentation.

## Credits

### Contributors
- [Ben Wann](https://github.com/tickbw)
- [Karl Anderson](https://github.com/k-anderson)
- [James Aimonetti](https://github.com/jamesaimonetti)
- [Darren Schreiber](https://github.com/dschreiber)
- [Peter Defebvre](https://github.com/macpie)
- [Francis Genet](https://github.com/frifri)
- [Sean Wysor](https://github.com/swysor)
- Thanks to [KnpLabs/php-github-api](https://github.com/KnpLabs/php-github-api) for their work on the Php github api, which inspired this library

Thanks to GitHub for the high quality API and documentation.
