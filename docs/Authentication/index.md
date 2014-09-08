# Authentication

## The Token Object
The Kazoo API authenticates and authorizes all requests based on a token.  This token is short lived and should be generated for each session.  There are several ways that a Kazoo authentication/authorization token can be generated, but they all involve providing secret credintials.  As such when using the SDK it is first necessary to create a AuthToken object from some set of credintials then provide that object to the SDK.

## Automatic Account ID
When an authentication/authorization token is granted by the Kazoo API, an associated account id is determined.  These token are limited to accessing that account or any sub-account.  As a convience the SDK will automatically use the account id of the authentication token if no other accountid is provided.  This allows a developer to simply use the Account object in API chains without having to specify the account id each time, unless they are attempting to access sub-accounts.

```php
$authAccount = $client->Account();

$subAccount = $client->Account("a0f3b6f2c5c0c95240993acd1bd6e762");
```

## Using the Token Object

```php
/* Code to generate $authToken as described in this section */
$options = array('base_url' => 'http://kazoo-crossbar-url:8000');
$client = new \Kazoo\SDK($authToken, $options);
```
