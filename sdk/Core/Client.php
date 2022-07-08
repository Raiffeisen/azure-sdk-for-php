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

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface
    {
        throw new \Exception('Async operations are not supported yet');
    }

    /**
     * {@inheritDoc}
     */
    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        $this->beforeSend($request, $options);
        $result = parent::send($request, $options);
        $this->afterSend($result);
        return $result;
    }

    /**
     * This function is called before every operation.
     * @param RequestInterface $request The request which is going to get executed.
     * @param array $options Request options to apply to the given request and to the transfer.
     *
     * @see \GuzzleHttp\RequestOptions
     * @return void
     */
    abstract protected function beforeSend(RequestInterface $request, array $options = []): void;

    /**
     * This function is called after every operation.
     * @param ResponseInterface $response The response from object.
     * @return void
     */
    abstract protected function afterSend(ResponseInterface $response): void;
}
