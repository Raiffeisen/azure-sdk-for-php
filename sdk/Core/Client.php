<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core;

abstract class Client extends \GuzzleHttp\Client
{
    /**
     * @var string The Api version
     */
    protected string $apiVersion;

    /**
     * @var array The baseUri parsed
     */
    protected array $baseUri;

    /**
     * Initialize
     * @param string $endpoint The endpoint URL.
     * @param string $apiVersion The api version.
     * @param array $config Client configuration settings.
     */
    public function __construct(string $endpoint, string $apiVersion, array $config = [])
    {
        $this->apiVersion = $apiVersion;
        $config['base_uri'] = $endpoint;

        $this->baseUri = parse_url($endpoint);

        parent::__construct($config);
    }
}
