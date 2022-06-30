<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication;

use Azure\Core\AccessToken;

class JwtTokenParser
{
    /**
     * Create an access token from jwt payload.
     * @param string $token The access token.
     * @return AccessToken
     * @throws \Exception
     */
    public static function CreateAccessToken(string $token): AccessToken
    {
        $payload = self::DecodeJwtPayload($token);

        return new AccessToken($token, $payload->getExpiresOn());
    }

    /**
     * Decode jwt payload
     * @param string $token The token to decode.
     * @return JwtPayload
     * @throws \Exception
     */
    public static function DecodeJwtPayload(string $token): JwtPayload
    {
        $tokenNotFormattedCorrectly = 'Token is not formatted correctly.';

        $tokenParts = explode('.', $token);
        if (count($tokenParts) < 2) {
            throw new \Exception($tokenNotFormattedCorrectly);
        }

        $data = json_decode(base64_decode($tokenParts[1]), true);

        return new JwtPayload($data['exp'], $data['acsScope']);
    }
}
