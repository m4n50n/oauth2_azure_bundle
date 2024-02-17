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

namespace M4n50n\OAuth2AzureBundle\DependencyInjection;

use M4n50n\OAuth2AzureBundle\Service\OAuth2AzureService;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class OAuth2AzureExtension
 * @package M4n50n\OAuth2AzureBundle\DependencyInjection
 */
final class OAuth2AzureExtension extends Extension
{
    /**
     * Loads a specific configuration.
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        // Process configuration
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Register OAuth2AzureService as a service
        $container->register("OAuth2AzureService", OAuth2AzureService::class)
            ->setArguments([$config]);

        // Load additional services from services.php
        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__ . "/../Resources/config"));
        $loader->load("services.php");
    }
}
