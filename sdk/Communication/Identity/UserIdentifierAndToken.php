<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity;

use Azure\Core\Model;
use GuzzleHttp\Utils;

/**
 * A communication identity with access token.
 *
 * @property AccessToken $accessToken An access token.
 * @property Identity $identity A communication identity.
 */
class UserIdentifierAndToken extends Model
{
    /**
     * Initialize a new UserIdentifierAndToken from json data.
     * @param string $json The json data to parse.
     * @return static
     */
    public static function fromJson(string $json): self
    {
        $data = Utils::jsonDecode($json, true);
        $result = new self();
        $result->identity = new Identity($data['identity']['id']);
        $result->accessToken = new AccessToken($data['accessToken']['token'], $data['accessToken']['expiresOn']);

        return $result;
    }
}
