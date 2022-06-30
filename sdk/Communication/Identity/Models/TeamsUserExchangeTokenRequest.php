<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity\Models;

class TeamsUserExchangeTokenRequest extends RequestObject
{
    /**
     * @var string
     */
    private $_token;
    /**
     * @var string
     */
    private $_appId;
    /**
     * @var string
     */
    private $_userId;

    /**
     * Initializes a new instance of TeamsUserExchangeTokenRequest.
     * @param string $token Azure AD access token of a Teams User to acquire a new Communication Identity access token.
     * @param string $appId Client ID of an Azure AD application to be verified against the appid claim in the Azure AD access token.
     * @param string $userId Object ID of an Azure AD user (Teams User) to be verified against the oid claim in the Azure AD access token.
     */
    public function __construct(string $token, string $appId, string $userId)
    {
        $this->_token = $token;
        $this->_appId = $appId;
        $this->_userId = $userId;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->_token;
    }

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->_appId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->_userId;
    }
}
