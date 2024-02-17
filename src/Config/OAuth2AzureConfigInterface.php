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

/**
 * Interface for the configuration of Azure OAuth2.
 * 
 * Defines the methods that the configuration object must implement.
 */
interface OAuth2AzureConfigInterface
{
    /**
     * Hydrates the configuration properties.
     *
     * @param array $config
     */
    public function hydrate(array $config): void;

    /**
     * Gets the client ID.
     *
     * @return string
     */
    public function clientId(): string;

    /**
     * Gets the client secret.
     *
     * @return string
     */
    public function clientSecret(): string;

    /**
     * Gets the tenant.
     *
     * @return string
     */
    public function tenant(): string;

    /**
     * Gets the redirect URI.
     *
     * @return string
     */
    public function redirectUri(): string;

    /**
     * Gets if the redirect URL after authentication is enabled.
     *
     * @return ?bool
     */
    public function redirectToUrl(): ?bool;

    /**
     * Gets the redirect URL after authentication.
     *
     * @return ?string
     */
    public function redirectUrl(): ?string;

    /**
     * Gets the authorization URL.
     *
     * @return string
     */
    public function urlAuthorize(): string;

    /**
     * Gets the access token URL.
     *
     * @return string
     */
    public function urlAccessToken(): string;

    /**
     * @return array Returns a serialized representation of the object as an associative array.
     */
    public function serialize(): array;
}
