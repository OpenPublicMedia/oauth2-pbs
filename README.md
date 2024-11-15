# PBS Provider for Public Media SSO Client

This package provides Public Media SSO support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Legacy PBS OAuth 2.0

For legacy PBS OAuth 2.0 support, use a 1.x versions of this library.

## Installation

To install, use composer:

```shell
composer require openpublicmedia/oauth2-pbs
```

## Usage

Usage is the same as The League's OAuth client, using `OpenPublicMedia\OAuth2\Client\Provider\Pbs` as the provider.

### Authorization Code Flow

```php
$provider = new OpenPublicMedia\OAuth2\Client\Provider\Pbs([
    'clientId'          => '{pbs-client-id}',
    'customerId'        => '{pbs-customer-id}',
    'redirectUri'       => 'https://example.com/callback-url',
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();

    // Add PKCE code to session.
    $_SESSION['oauth2pkceCode'] = $provider->getPkceCode();

    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Restore the PKCE code stored in the session.
    $provider->setPkceCode($_SESSION['oauth2pkceCode']);

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getFirstName());

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Christopher C. Wells](https://www.chris-wells.net)
- [All Contributors](https://github.com/openpublicmedia/oauth2-pbs/graphs/contributors)


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
