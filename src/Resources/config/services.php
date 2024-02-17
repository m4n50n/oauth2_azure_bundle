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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use M4n50n\OAuth2AzureBundle\EventListener\OAuth2AzureListener;
use M4n50n\OAuth2AzureBundle\Factory\OAuth2AzureFactory;
use Symfony\Component\DependencyInjection\Reference;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->set(OAuth2AzureFactory::class)
        ->args([
            new Reference('OAuth2AzureService')
        ]);

    $container->services()
        ->set(OAuth2AzureListener::class)
        ->tag('kernel.event_listener', ['event' => 'kernel.request', 'method' => 'onKernelRequest'])
        ->args([
            new Reference('OAuth2AzureService')
        ]);
};
