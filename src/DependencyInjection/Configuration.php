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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package M4n50n\OAuth2AzureBundle\DependencyInjection
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder("oauth2_azure");

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode("clientId")->isRequired()->end()
                ->scalarNode("clientSecret")->isRequired()->end()
                ->scalarNode("tenant")->isRequired()->end()
                ->scalarNode("redirectUri")->isRequired()->end()
                ->booleanNode("redirectToUrl")->defaultValue(false)->end()
                ->scalarNode("redirectUrl")->defaultNull()->end()
            ->end();

        return $treeBuilder;
    }
}
