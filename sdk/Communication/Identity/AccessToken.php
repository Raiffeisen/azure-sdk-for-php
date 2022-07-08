<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity;

use Azure\Core\Model;
use GuzzleHttp\Utils;

/**
 * An access token.
 *
 * @property string $expiresOn The expiry time of the token.
 * @property string $token The access token issued for the identity.
 */
class AccessToken extends Model
{
    /**
     * Initialize a new access token.
     * @param string|null $token The access token issued for the identity.
     * @param string|null $expiresOn The expiry time of the token.
     */
    public function __construct(?string $token = null, ?string $expiresOn = null)
    {
        $this->token = $token;
        $this->expiresOn = $expiresOn;
    }

    /**
     * Initialize a new AccessToken from json data.
     * @param string $json The json data to parse.
     * @return static
     */
    public static function fromJson(string $json): self
    {
        $data = Utils::jsonDecode($json, true);
        return new self($data['token'], $data['expiresOn']);
    }
}
