<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Chat;

use Azure\Communication\CommunicationTokenCredential;
use Azure\Core\ClientOptions;
use Azure\Core\Pipeline\BearerTokenAuthenticationPolicy;
use Azure\Core\Pipeline\HttpPipeline;

/**
 * The options for communication ChatClient.
 * @see ChatClient
 */
class ChatClientOptions extends ClientOptions
{
    private const V2021_03_07 = 1;
    private const V2021_09_07 = 2;
    private const LATEST_VERSION = self::V2021_09_07;

    private $_apiVersion;

    /**
     * Initializes a new instance of the ChatClientOptions.
     * @param int $version The api version to use.
     */
    public function __construct(int $version = self::LATEST_VERSION)
    {
        switch ($version) {
            case self::V2021_03_07:
                $this->_apiVersion = '2021-03-07';
                break;
            case self::V2021_09_07:
                $this->_apiVersion = '2021-09-07';
                break;
            default:
                throw new \InvalidArgumentException("Version $version is not known.");
        }
    }

    /**
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
    public static function buildHttpPipeline(CommunicationTokenCredential $communicationTokenCredential): HttpPipeline
    {
        $bearerTokenCredential = new CommunicationBearerTokenCredential($communicationTokenCredential);
        $authPolicy = new BearerTokenAuthenticationPolicy($bearerTokenCredential, '');
        return new HttpPipeline(null, [$authPolicy]);
    }
}
