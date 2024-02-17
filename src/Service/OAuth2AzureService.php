<?php

declare(strict_types=1);

/**
 * This file is part of the OAuth2AzureBundle package.
 *
 * (c) Jose Clemente García Rodríguez aka m4n50n <josegarciarodriguez89@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @author Jose Clemente García Rodríguez <josegarciarodriguez89@hotmail.com>
 * 
 * @link https://github.com/m4n50n/oauth2_azure_bundle
 */

namespace M4n50n\OAuth2AzureBundle\Service;

use M4n50n\OAuth2AzureBundle\Config\OAuth2AzureConfig;
use M4n50n\OAuth2AzureBundle\Trait\StandardResponseTrait;
use Symfony\Component\HttpFoundation\Request;
use TheNetworg\OAuth2\Client\Provider\Azure;
use TheNetworg\OAuth2\Client\Token\AccessToken;

/**
 * Class OAuth2AzureService
 * @package M4n50n\OAuth2AzureBundle\Service
 */
class OAuth2AzureService
{
    use StandardResponseTrait;

    public OAuth2AzureConfig $config;
    public Azure $provider;

    /**
     * OAuth2AzureService constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new OAuth2AzureConfig($config);

        $this->provider = new Azure($this->config->serialize());
        $this->provider->scope = "openid profile email offline_access " . $this->provider->getRootMicrosoftGraphUri(null) . "/User.Read";
    }

    /**
     * Handles the authentication process.
     *
     * @param Request $request
     * @return array
     */
    public function auth(Request $request): array
    {
        try {
            [$code, $error, $message, $data, $object] = [200, false, "OK", [], []];
            $this->initResponse($code, $error, $message, $data, $object);

            $session = $request->getSession();
            $sessionOAuthState = $session->get("OAuth2.state");

            if ($sessionOAuthState && $request->query->has("code") && $request->query->has("state")) {
                if ($request->query->get("state") == $sessionOAuthState) {
                    $session->set("OAuth2.state", "");

                    // Try to get an access token (using the authorization code grant)
                    /** @var AccessToken $token */
                    $token = $this->provider->getAccessToken("authorization_code", [
                        "scope" => $this->provider->scope,
                        "code" => $request->query->get("code"),
                    ]);

                    // Saving token to local server session data
                    $session->set("OAuth2.state", $token);

                    // Get owner data
                    $this->setResponse($this->getOwnerData($token));
                    if (!$this->isError()) {
                        $ownerData = $this->getObject();

                        [$message, $data, $object] = ["Authorized", $ownerData, $token->getToken()];
                    } else {
                        [$code, $error, $message] = [500, true, "Error getting user data from provider"];
                    }
                } else {
                    [$code, $error, $message] = [400, true, "Invalid state"];
                }
            } else {
                $message = "getAuthorization";
                $object = $this->provider->getAuthorizationUrl(["scope" => $this->provider->scope]);
                $session->set("OAuth2.state", $this->provider->getState());
            }

            $this->initResponse($code, $error, $message, $data, $object);
        } catch (\Exception $exception) {
            $this->initResponse(500, true, sprintf('Error: %s in class: ', $exception->getMessage()) . self::class . ", method: " . __FUNCTION__ . ", file: " . __FILE__ . ", line: " . __LINE__);
        }

        return $this->getResponse();
    }

    /**
     * Gets owner data based on the provided access token.
     *
     * @param AccessToken $token
     * @return array
     */
    public function getOwnerData(AccessToken $token): array
    {
        try {
            [$code, $error, $message, $data, $object] = [200, false, "OK", [], []];
            $this->initResponse($code, $error, $message, $data, $object);

            $resourceOwner = $this->provider->getResourceOwner($token);
            $ownerData = $resourceOwner->toArray();

            $additionalData = [
                "refreshToken" => $token->getRefreshToken()
            ];

            $this->setResponse($this->getProfileImage($token));
            if (!$this->isError()) {
                $additionalData["profileImage"] = base64_encode($this->getObject());
            }

            $object = array_merge($ownerData, $additionalData);

            $this->initResponse($code, $error, $message, $data, $object);
        } catch (\Exception $exception) {
            $this->initResponse(500, true, sprintf('Error: %s in class: ', $exception->getMessage()) . self::class . ", method: " . __FUNCTION__ . ", file: " . __FILE__ . ", line: " . __LINE__);
        }

        return $this->getResponse();
    }

    /**
     * Gets the profile image based on the provided access token.
     *
     * @param AccessToken $token
     * @return array
     */
    public function getProfileImage(AccessToken $token): array
    {
        try {
            [$code, $error, $message, $data, $object] = [200, false, "OK", [], []];
            $this->initResponse($code, $error, $message, $data, $object);

            $graphApiUrlPixels = 'https://graph.microsoft.com/v1.0/me/photos/48x48/$value';

            $ownerProfileImageRequest = $this->provider->getAuthenticatedRequest("GET", $graphApiUrlPixels, $token);
            $binaryProfileImage = $this->provider
                ->getResponse($ownerProfileImageRequest)
                ->getBody()
                ->getContents();

            $object = $binaryProfileImage;

            $this->initResponse($code, $error, $message, $data, $object);
        } catch (\Exception $exception) {
            $this->initResponse(500, true, sprintf('Error: %s in class: ', $exception->getMessage()) . self::class . ", method: " . __FUNCTION__ . ", file: " . __FILE__ . ", line: " . __LINE__);
        }

        return $this->getResponse();
    }
}
