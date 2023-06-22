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
 */
class UserIdentifierAndToken extends Model
{
    /** @var AccessToken An access token. */
    public AccessToken $accessToken;

    /** @var Identity A communication identity. */
    public Identity $identity;

    /**
     * Initialize a new UserIdentifierAndToken from json data.
     * @param string $json The json data to parse.
     * @return static
     * @throws \Exception
     */
    public static function fromJson(string $json): self
    {
        $data = Utils::jsonDecode($json, true);
        return new self([
            'identity' => new Identity($data['identity']['id']),
            'accessToken' => new AccessToken($data['accessToken']['token'], $data['accessToken']['expiresOn'])
        ]);
    }
}
