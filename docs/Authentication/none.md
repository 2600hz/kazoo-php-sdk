# Server-Side

Using the noauth crossbar modules it is possible to disable authentication for all API requests.  Furthermore, the ip_auth module will authenticate requests from known IP addresses.  In either case the SDK should not attempt to create an authetication token.  In these cases it is necessary to generate a dummy AuthToken object.

## Creating an AuthToken object
```php
$authToken = new Kazoo\AuthToken\None();
```
