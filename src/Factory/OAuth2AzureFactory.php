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
use M4n50n\OAuth2AzureBundle\Service\OAuth2AzureService;
use M4n50n\OAuth2AzureBundle\Trait\StandardResponseTrait;
use Symfony\Component\HttpFoundation\Request;

final class OAuth2AzureFactory implements OAuth2AzureFactoryInterface
{
    use StandardResponseTrait;


    /**
     * Constructor. 
     * 
     * @var OAuth2AzureService OAuth2Azure authentication service.
     */
    public function __construct(private OAuth2AzureService $OAuth2AzureService)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getAuth(Request $request): AuthResponse
    {
        try {
            return $this->handleAuth($request);
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig(): OAuth2AzureConfig
    {
        return $this->OAuth2AzureService->config;
    }

    /**
     * Handles authentication.
     *
     * @param Request $request The HTTP request.
     * 
     * @return AuthResponse The authentication response.
     * 
     * @throws \Exception If an error occurs.
     */
    private function handleAuth(Request $request): AuthResponse
    {
        $this->setResponse($this->OAuth2AzureService->auth($request));

        if (!$this->isError()) {
            return $this->handleSuccessfulAuth();
        } else {
            throw new \Exception(sprintf('Error: %s', $this->getMessage()) . self::class . ", method: " . __FUNCTION__ . ", file: " . __FILE__ . ", line: " . __LINE__);
        }
    }

    /**
     * Handles successful authentication.
     *
     * @return AuthResponse The authentication response.
     * 
     * @throws \Exception If the response is invalid.
     */
    private function handleSuccessfulAuth(): AuthResponse
    {
        $message = $this->getMessage();

        if ("getAuthorization" === $message) {
            $this->redirect($this->getObject());
        } elseif ("Authorized" === $message) {
            $ownerData = $this->getData();

            return $this->handleAuthorized($ownerData);
        } else {
            throw new \Exception("Invalid response");
        }
    }

    /**
     * Handles authorized response.
     *
     * @param array $ownerData Owner data.
     * 
     * @return AuthResponse The authorization response.
     */
    private function handleAuthorized(array $ownerData)
    {
        $redirectToUrl = $this->OAuth2AzureService->config->redirectToUrl();
        $redirectUrl = $this->OAuth2AzureService->config->redirectUrl();

        if ($redirectToUrl) {
            return $this->redirect($redirectUrl);
        }

        // If no redirect URL is set
        return new AuthResponse(false, $ownerData);
    }

    /**
     * Handles an exception.
     *
     * @param \Exception $exception The exception.
     * 
     * @throws \Exception If an error occurs.
     */
    private function handleException(\Exception $exception): void
    {
        throw new \Exception(sprintf('Error: %s', $exception->getMessage()) . self::class . ", method: " . __FUNCTION__ . ", file: " . __FILE__ . ", line: " . __LINE__);
    }

    /**
     * Redirects to the provided URL.
     *
     * @param string $url The URL to redirect to.
     */
    private function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }
}
