<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication;

class StaticTokenCredential
{
    private $_accessToken;

    /**
     * Initialize a new StaticTokenCredential instance.
     * @param string $token
     * @throws \Exception
     */
    public function __construct(string $token)
    {
        $this->_accessToken = JwtTokenParser::CreateAccessToken($token);
    }

    /**
     * Get the access token
     * @param mixed $cancellationToken
     * @return \Azure\Core\AccessToken
     */
    public function getToken($cancellationToken = null): \Azure\Core\AccessToken
    {
        return $this->_accessToken;
    }

    /**
     * Get the access token async
     * @param mixed $cancellationToken
     * @return \Azure\Core\AccessToken
     */
    public function getTokenAsync($cancellationToken = null): \Azure\Core\AccessToken
    {
        return $this->getToken($cancellationToken);
    }
}
