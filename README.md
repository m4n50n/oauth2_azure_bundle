OAuth 2.0 for Azure Bundle
===================
[![Latest Stable Version](https://poser.pugx.org/m4n50n/oauth2-azure-bundle/v/stable)](https://packagist.org/packages/m4n50n/oauth2-azure-bundle)
[![License](https://poser.pugx.org/m4n50n/oauth2-azure-bundle/license)](LICENSE.md)
[![Total Downloads](https://poser.pugx.org/m4n50n/oauth2-azure-bundle/downloads)](https://packagist.org/packages/m4n50n/oauth2-azure-bundle)

This Symfony bundle serves as a tiny wrapper for the [Azure Active Directory Provider for OAuth 2.0 Client](https://github.com/TheNetworg/oauth2-azure). You can find additional documentation in the official repository.


## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```
symfony composer require m4n50n/oauth2-azure-bundle
```

### Enable the Bundle

Enable the bundle by adding it to the list of registered bundles in the `config/bundles.php` file of your project.

```php
// config/bundles.php

return [
    // ...    
    M4n50n\OAuth2AzureBundle\OAuth2AzureBundle::class => ['all' => true],
];
```

## Configure the Bundle

Configure the bundle in the `config/packages/oauth2_azure.yaml` file:

```yaml
# config/packages/oauth2_azure.yaml

o_auth2_azure:
  clientId: "%env(AUTH_CLIEN_ID)%"
  clientSecret: "%env(AUTH_CLIENT_PASS)%"
  tenant: "%env(AUTH_TENANT)%"
  redirectUri: "%env(AUTH_REDIRECT_URI)%"

  # Optional
  redirectToUrl: "%env(bool:AUTH_REDIRECT_TO_URL)%" # Activate redirect after authentication
  redirectUrl: "%env(AUTH_REDIRECT_URL)%" # URL to redirect after authentication
```

```bash
# .env

AUTH_CLIEN_ID="c3db02f0-401c-452c......"
AUTH_CLIENT_PASS="LfR8Q~yTXB5ozRejLrqE6oYqp......"
AUTH_TENANT="5fa120f8-1ee1-49e3-9b......"
AUTH_REDIRECT_URI="https://endpoint.com/api/login/azure"
AUTH_REDIRECT_TO_URL=true
AUTH_REDIRECT_URL="https://endpoint-client.com"
```

## Usage

Inject ***OAuth2AzureFactory*** into your Service or Controller, and call the *getAuth()* method with *Request* as an argument.

If the *redirectToUrl* configuration parameter exists and has a *true* value, it will be redirected to the *redirectUrl* set after authentication. Otherwise, an ***AuthResponse*** object will be returned, containing the getOwnerData() method, which returns the data of the Azure-authenticated account.

```php
use M4n50n\OAuth2AzureBundle\Factory\OAuth2AzureFactory;

final class LoginController extends AbstractController
{
    public function __construct(private OAuth2AzureFactory $OAuth2AzureFactory)
    {
    }

    #[Route(path: '/login/azure', name: 'login_azure', methods: ['GET'])]
    public function user_azureLoginRequest(JWTTokenManagerInterface $JWTManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        try {
            // ...

            $auth = $this->OAuth2AzureFactory->getAuth($this->request);
            $ownerData = $auth->getOwnerData();

            /* It returns an array with the following structure:

            $ownerData = [
                "aud" => "c3db02f0-401c-452c......",
                "iss" => "https://login.microsoftonline.com/....../v2.0",
                "iat" => 1360114,
                "profileImage" => "", // base64_encode of the image binary
                "email":"josegarciarodriguez89@hotmail.com",
                "name":"Jose Garcia",
                
                // ... (other fields)
            ];              
            */

            // ...
        } catch (\Exception $exception) {
            // ...
        }

        // ...
    }
}
```

### Methods

This wrapper defines the following methods:

- Class `OAuth2AzureFactory`: `getAuth()` starts the user authentication flow.
- Class `OAuth2AzureFactory`: `getConfig()` returns the entire bundle configuration object.
- Class `AuthResponse`: `isError()` returns if there has been an error in the authentication process.

## Contributing

See [CONTRIBUTING](CONTRIBUTING.md) for more information.

## Security

See [SECURITY](SECURITY.md) for more information.

## License

Please see the [LICENSE](LICENSE) included in this repository for a full copy of the MIT license, which this project is licensed under.