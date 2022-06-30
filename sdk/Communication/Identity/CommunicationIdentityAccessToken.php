<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity;

class CommunicationIdentityAccessToken
{
    /**
     * @var string The access token issued for the identity
     */
    public $token;

    /**
     * @var \DateInterval The expiry time of the token
     */
    public $expiresOn;

    /**
     * Initializes a new CommunicationIdentityAccessToken
     * @param string $token The access token issued for the identity.
     * @param \DateInterval $expiresOn The expiry time of the token.
     */
    public function __construct(string $token, \DateInterval $expiresOn)
    {
        $this->token = $token;
        $this->expiresOn = $expiresOn;
    }

    /**
     * Create a CommunicationIdentityAccessToken out of a json deserialized array.
     * @param array $element The deserialized json string.
     * @return self
     * @throws \Exception
     */
    public static function deserializeCommunicationIdentityAccessToken(array $element): self
    {
        $token = null;
        $expiresOn = null;
        foreach ($element as $name => $value) {
            if ($name === 'token') {
                $token = $value;
            } elseif ($name === 'expiresOn') {
                $expiresOn = new \DateInterval($value);
            }
        }

        return new self($token, $expiresOn);
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return \DateInterval
     */
    public function getExpiresOn()
    {
        return $this->expiresOn;
    }


}
