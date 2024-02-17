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

namespace M4n50n\OAuth2AzureBundle\Factory;

use M4n50n\OAuth2AzureBundle\Config\OAuth2AzureConfig;
use M4n50n\OAuth2AzureBundle\Response\AuthResponse;
use Symfony\Component\HttpFoundation\Request;

interface OAuth2AzureFactoryInterface
{
    /**
     * Gets the authentication response.
     *
     * @param Request $request The HTTP request.
     * 
     * @return AuthResponse The authentication response.
     */
    public function getAuth(Request $request): AuthResponse;

    /**
     * Get the bundle config object.
     *
     * @return OAuth2AzureConfig
     */
    public function getConfig(): OAuth2AzureConfig;
}
