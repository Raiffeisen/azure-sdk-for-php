<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity\Models;

/**
 * Options used to exchange an AAD access token of a Teams user for a new Communication Identity access token.
 */
class GetTokenForTeamsUserOptions
{
    private $_teamsUserAadToken;
    private $_clientId;
    private $_userObjectId;

    /**
     *  Initializes a new instance of GetTokenForTeamsUserOptions.
     * @param string $teamsUserAadToken Azure AD access token of a Teams User.
     * @param string $clientId Client ID of an Azure AD application to be verified against the appId claim in the Azure AD access token.
     * @param string $userObjectId Object ID of an Azure AD user (Teams User) to be verified against the OID claim in the Azure AD access token.
     */
    public function __construct(string $teamsUserAadToken, string $clientId, string $userObjectId)
    {
        $this->_teamsUserAadToken = $teamsUserAadToken;
        $this->_clientId = $clientId;
        $this->_userObjectId = $userObjectId;
    }

    /**
     * Azure AD access token of a Teams user.
     * @return string
     */
    public function getTeamsUserAadToken(): string
    {
        return $this->_teamsUserAadToken;
    }

    /**
     * Client ID of an Azure AD application to be verified against the appId claim in the Azure AD access token.
     * @return string
     */
    public function getClientId(): string
    {
        return $this->_clientId;
    }

    /**
     * Object ID of an Azure AD user (Teams User) to be verified against the OID claim in the Azure AD access token.
     * @return string
     */
    public function getUserObjectId(): string
    {
        return $this->_userObjectId;
    }
}
