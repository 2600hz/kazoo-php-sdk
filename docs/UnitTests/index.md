# Unit Tests

## Running Unit Tests

_NOTICE: The unit test will create accounts and other entities.  It is recommended that you only run them on development environments_

* Ensure you have the latest dependencies via composer
```bash
$ ./composer update
```
* Edit the phpunit.xml file and update the following parameters
  * base_url
  * api_key
  * auth_username
  * auth_password
  * auth_realm
```bash
$ vim lib/hz2600/Kazoo/Tests/phpunit.xml
```
* Run the unit test
```bash
lib/hz2600/Kazoo/Tests/runtests.sh
```

## Running Certain Tests

Any arguments provided to `runtests.sh` will be pased along to [phpunit](https://phpunit.de/manual/current/en/textui.html#textui.clioptions).  One of the more useful is `--filter`.  For example, to just test the devices API:

```bash
lib/hz2600/Kazoo/Tests/runtests.sh --filter DeviceTest
```

## Futher Info
* [Creating Tests](create_test.md)
* [Common Test Classes](common.md)
