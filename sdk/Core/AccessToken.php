<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core;

class AccessToken
{
    /**
     * @var string Get the access token value.
     */
    public $token;
    /**
     * @var \DateInterval Gets the time when the provided token expires.
     */
    public $expiresOn;

    /**
     * Creates a new instance of AccessToken using the provided token and expiresOn.
     * @param string $accessToken The bearer access token value.
     * @param \DateInterval $expiresOn The bearer access token expiry date.
     */
    public function __construct(string $accessToken, \DateInterval $expiresOn)
    {
        $this->token = $accessToken;
        $this->expiresOn = $expiresOn;
    }
}
