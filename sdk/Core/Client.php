<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Client extends \GuzzleHttp\Client
{
    /**
     * @var string The Api version
     */
    protected string $apiVersion;

    /**
     * Initialize
     * @param string $endpoint The endpoint URL.
     * @param string $apiVersion The api version.
     * @param array $config Client configuration settings.
     */
    public function __construct(string $endpoint, string $apiVersion = '2022-06-01', array $config = [])
    {
        $this->apiVersion = $apiVersion;
        $config['base_uri'] = $endpoint;

        parent::__construct($config);
    }
}
