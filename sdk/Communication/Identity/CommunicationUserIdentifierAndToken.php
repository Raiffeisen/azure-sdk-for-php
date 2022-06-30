<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity;

use Azure\Communication\CommunicationUserIdentifier;
use Azure\Communication\Identity\Models\CommunicationIdentity;

class CommunicationUserIdentifierAndToken
{
    /**
     * @var CommunicationIdentityAccessToken The bearer access token.
     */
    private $_accessToken;
    /**
     * @var CommunicationIdentity
     */
    private $_identity;
    /**
     * @var CommunicationUserIdentifier The user identifier
     */
    private $_user;

    public function __construct(CommunicationIdentity $identity, CommunicationIdentityAccessToken $accessToken)
    {
        $this->_accessToken = $accessToken;
        $this->_identity = $identity;
        $this->_user = new CommunicationUserIdentifier($identity->id);
    }

    /**
     * Create a CommunicationUserIdentifierAndToken out of a json deserialized array.
     * @param array $element The deserialized json string.
     * @return self
     * @throws \Exception
     */
    public static function deserializeCommunicationUserIdentifierAndToken(array $element): self
    {
        $identity = null;
        $accessToken = null;
        foreach ($element as $name => $value) {
            if ($name === 'identity') {
                $identity = CommunicationIdentity::deserializeCommunicationIdentity($value);
            } elseif ($name === 'accessToken') {
                $accessToken = CommunicationIdentityAccessToken::deserializeCommunicationIdentityAccessToken($value);
            }
        }

        return new self($identity, $accessToken);
    }

    /**
     * @return CommunicationIdentityAccessToken
     */
    public function getAccessToken(): CommunicationIdentityAccessToken
    {
        return $this->_accessToken;
    }

    /**
     * @return CommunicationUserIdentifier
     */
    public function getUser(): CommunicationUserIdentifier
    {
        return $this->_user;
    }

    /**
     * @return CommunicationIdentity
     */
    public function getIdentity(): CommunicationIdentity
    {
        return $this->_identity;
    }
}
