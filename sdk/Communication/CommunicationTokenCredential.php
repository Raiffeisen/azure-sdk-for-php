<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication;

/**
 * The Azure Communication Services Token Credential.
 */
class CommunicationTokenCredential
{
    private $_tokenCredential;

    /**
     * Initializes a new CommunicationTokenCredential.
     * @param string $token User token acquired from Azure.Communication.Administration package.
     * @throws \Exception
     */
    public function __construct(string $token)
    {
        $this->_tokenCredential = new StaticTokenCredential($token);
    }

    /**
     * Gets an AccessToken for the user.
     * @param mixed $cancellationToken The cancellation token for the task.
     * @return \Azure\Core\AccessToken
     */
    public function getTokenAsync($cancellationToken = null): \Azure\Core\AccessToken
    {
        return $this->_tokenCredential->getTokenAsync($cancellationToken);
    }

    /**
     * Gets an AccessToken for the user.
     * @param mixed $cancellationToken The cancellation token for the task.
     * @return \Azure\Core\AccessToken Contains the access token for the user.
     */
    public function getToken($cancellationToken = null): \Azure\Core\AccessToken
    {
        return $this->_tokenCredential->getToken($cancellationToken);
    }
}
