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
 */
class AccessToken extends Model
{
    /** @var \DateTime The expiry time of the token. */
    public \DateTime $expiresOn;

    /** @var string The access token issued for the identity. */
    public string $token;

    /**
     * Initialize a new access token.
     * @param string $token The access token issued for the identity.
     * @param string $expiresOn The expiry time of the token.
     * @throws \Exception
     */
    public function __construct(string $token, string $expiresOn, array $config = [])
    {
        $this->token = $token;
        $this->expiresOn = $this->parseDateTime($expiresOn);
        parent::__construct($config);
    }

    /**
     * Parses a date time string to a \DateTime object
     * @param string $value
     * @return \DateTime|false
     * @throws \Exception
     */
    public function parseDateTime(string $value): \DateTime|false
    {
        $value = new \DateTime($value, new \DateTimeZone('UTC'));
        $value->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        return $value;
    }

    /**
     * Initialize a new AccessToken from json data.
     * @param string $json The json data to parse.
     * @return static
     * @throws \Exception
     */
    public static function fromJson(string $json): self
    {
        $data = Utils::jsonDecode($json, true);
        return new self($data['token'], $data['expiresOn']);
    }
}
