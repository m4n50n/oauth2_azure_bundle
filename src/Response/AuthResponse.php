<?php

namespace M4n50n\OAuth2AzureBundle\Response;

/**
 * Class AuthResponse 
 */
class AuthResponse
{
    private $error;
    private $ownerData;

    /**
     * Constructor.
     *     
     * @param bool  $error
     * @param array $ownerData
     */
    public function __construct(bool $error, array $ownerData)
    {
        $this->error = $error;
        $this->ownerData = $ownerData;
    }

    /**
     * Check if there was an error.
     *
     * @return bool
     */
    public function isError(): bool
    {
        return $this->error;
    }

    /**
     * Get the auth owner data.
     *
     * @return array
     */
    public function getOwnerData(): array
    {
        return $this->ownerData;
    }
}
