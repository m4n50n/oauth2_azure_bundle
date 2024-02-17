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

namespace M4n50n\OAuth2AzureBundle\EventListener;

use M4n50n\OAuth2AzureBundle\Service\OAuth2AzureService;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class OAuth2AzureListener
 * @package M4n50n\OAuth2AzureBundle\EventListener
 */
class OAuth2AzureListener
{
    /**
     * OAuth2AzureListener constructor.
     * @param OAuth2AzureService $OAuth2AzureService
     */
    public function __construct(private OAuth2AzureService $OAuth2AzureService)
    {
    }

    /**
     * Handles the kernel.request event.
     *
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event)
    {
        // Uncomment and use $request if needed
        // $request = $event->getRequest();
    }
}
