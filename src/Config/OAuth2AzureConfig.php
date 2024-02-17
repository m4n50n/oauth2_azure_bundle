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

namespace M4n50n\OAuth2AzureBundle\Config;

use TheNetworg\OAuth2\Client\Provider\Azure;

/**
 * Class OAuth2AzureConfig
 * @package M4n50n\OAuth2AzureBundle\Config
 */
class OAuth2AzureConfig implements OAuth2AzureConfigInterface
{
    private const OAUTH2_URL_BASE = "https://login.microsoftonline.com/";
    private const AUTHORIZATION_TYPE = "authorize";
    private const TOKEN_TYPE = "token";

    private array $default = [
        "scopes" => ["openid"],

        // Set to use v2 API, skip the line or set the value to Azure::ENDPOINT_VERSION_1_0 if willing to use v1 API
        "defaultEndPointVersion" => Azure::ENDPOINT_VERSION_2_0
    ];

    private string $clientId;
    private string $clientSecret;
    private string $tenant;
    private string $redirectUri;    
    private ?bool $redirectToUrl;
    private ?string $redirectUrl;

    /**
     * OAuth2AzureConfig constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->hydrate($config);
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate(array $config): void
    {
        foreach ($config as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function clientId(): string
    {
        return $this->clientId;
    }

    /**
     * {@inheritDoc}
     */
    public function clientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * {@inheritDoc}
     */
    public function tenant(): string
    {
        return $this->tenant;
    }

    /**
     * {@inheritDoc}
     */
    public function redirectUri(): string
    {
        return $this->redirectUri;
    }

    /**
     * {@inheritDoc}
     */
    public function redirectToUrl(): ?bool
    {
        return $this->redirectToUrl;
    }

    /**
     * {@inheritDoc}
     */
    public function redirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    /**
     * Validates the authorization type.
     *
     * @param string $type
     */
    private function validateType(string $type): void
    {
        if (!in_array($type, [self::AUTHORIZATION_TYPE, self::TOKEN_TYPE])) {
            throw new \InvalidArgumentException(sprintf('Invalid $type. Must be \'"%s"\' or \'"%s"\'.', self::AUTHORIZATION_TYPE, self::TOKEN_TYPE));
        }
    }

    /**
     * Builds the authorization or token URL based on the type.
     *
     * @param string $type
     * @return string
     */
    private function buildUrl(string $type): string
    {
        $this->validateType($type);

        return self::OAUTH2_URL_BASE . "{$this->tenant}/oauth2/v2.0/{$type}";
    }

    /**
     * {@inheritDoc}
     */
    public function urlAuthorize(): string
    {
        return $this->buildUrl("authorize");
    }

    /**
     * {@inheritDoc}
     */
    public function urlAccessToken(): string
    {
        return $this->buildUrl("token");
    }

    /**
     * Serializes the configuration to an array.
     *
     * @return array
     */
    public function serialize(): array
    {
        return array_merge($this->default, [
            "clientId" => $this->clientId(),
            "clientSecret" => $this->clientSecret(),
            "tenant" => $this->tenant(),
            "redirectUri" => $this->redirectUri(),
            "urlAuthorize" => $this->urlAuthorize(),
            "urlAPI" => $this->urlAuthorize(),
            "urlAccessToken" => $this->urlAccessToken()
        ]);
    }
}
