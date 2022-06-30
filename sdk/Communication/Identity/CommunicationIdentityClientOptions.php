<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity;

use Azure\Communication\Pipeline\HMACAuthenticationPolicy;
use Azure\Core\ClientOptions;
use Azure\Core\ConnectionString;
use Azure\Core\Pipeline\HttpPipeline;

/**
 * The options for communication
 */
class CommunicationIdentityClientOptions extends ClientOptions
{
    private const SERVICE_VERSION_V2021_03_07 = 1;
    private const SERVICE_VERSION_V2022_06_01 = 2;
    private const LATEST_VERSION = self::SERVICE_VERSION_V2022_06_01;

    /**
     * @var string
     */
    private $_apiVersion;

    /**
     * Initializes a new instance of the
     * @param int $version
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(int $version = self::LATEST_VERSION)
    {
        switch ($version) {
            case self::SERVICE_VERSION_V2021_03_07:
                $this->_apiVersion = '2021-03-07';
                break;
            case self::SERVICE_VERSION_V2022_06_01:
                $this->_apiVersion = '2022-06-01';
                break;
            default:
                throw new \InvalidArgumentException("Version $version is not known.");
        }
    }

    /**
     * Get the api version
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->_apiVersion;
    }

    /**
     * Build the http policy
     * @throws \Exception
     */
    public static function buildHttpPipeline(ConnectionString $connectionString): HttpPipeline
    {
        $authPolicy = new HMACAuthenticationPolicy($connectionString->getRequired('accesskey'));
        return new HttpPipeline(null, [$authPolicy]);
    }
}
