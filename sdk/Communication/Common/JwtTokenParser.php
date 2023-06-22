<?php

namespace Azure\Communication\Common;

use Azure\Communication\Identity\AccessToken;
use Exception;

class JwtTokenParser
{
    /**
     * Creates an AccessToken object from an access-token string.
     * @param string $token
     * @return AccessToken
     * @throws Exception
     */
    public static function createAccessToken(string $token): AccessToken
    {
        $payload = self::decodeJwtPayload($token);
        return new AccessToken($token, $payload->exp);
    }

    /**
     * @param string $token
     * @return mixed
     * @throws Exception
     */
    private static function decodeJwtPayload(string $token): mixed
    {
        $tokenParts = explode('.', $token);
        if (count($tokenParts) < 2) {
            throw new Exception("Token is not formatted correctly.");
        }

        try {
            $base64Decoded = base64_decode($tokenParts[1]);
            $payload = json_decode($base64Decoded);
            if ($payload === null) {
                throw new Exception("Token is not formatted correctly.");
            }
            return $payload;
        } catch (Exception $ex) {
            throw new Exception("Token is not formatted correctly.", 0, $ex);
        }
    }
}
