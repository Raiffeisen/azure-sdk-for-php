<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core;

use Azure\Core\Pipeline\HttpPipelinePolicy;

abstract class ClientOptions
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $_transport;
    /**
     * @var RetryOptions
     */
    protected $_retry;
    /**
     * @var HttpPipelinePolicy[]
     */
    protected $_policies = [];

    /**
     * Gets the default set of ClientOptions. Changes to the Default option would be reflected in new instances of
     * ClientOptions type created after changes to Default were made
     * @return static
     */
    public static function default(): ClientOptions
    {
        return new static();
    }

    /**
     * The HttpPipelineTransport to be used for this client. Defaults to an instance of \GuzzleHttp\Client.
     * @return \GuzzleHttp\ClientInterface
     */
    public function getTransport(): \GuzzleHttp\ClientInterface
    {
        return $this->_transport;
    }

    /**
     * The HttpPipelineTransport to be used for this client. Defaults to an instance of \GuzzleHttp\Client.
     * @param \GuzzleHttp\ClientInterface $transport
     * @return void
     */
    public function setTransport(\GuzzleHttp\ClientInterface $transport)
    {
        $this->_transport = $transport;
    }

    /**
     * Gets the client retry options.
     * @return RetryOptions
     */
    public function getRetry(): RetryOptions
    {
        return $this->_retry;
    }

    /**
     * Sets the client retry options.
     * @param RetryOptions $retry
     */
    public function setRetry(RetryOptions $retry): void
    {
        $this->_retry = $retry;
    }

    /**
     * Adds an HttpPipeline policy to the client pipeline. The position of policy is controlled by position parameter.
     * If you want the policy to execute once per client request use HttpPipelinePosition::PER_CALL otherwise use HttpPipelinePosition::PER_RETRY
     * to run the policy for every retry. Note hat the same instance of policy would be added to all pipelines of client constructed using this ClientOptions object.
     * @param HttpPipelinePolicy $policy The HttpPipelinePolicy instance to be added to the pipeline.
     * @param string $position The position of policy in the pipeline (one of HttpPipelinePosition::* constants).
     * @return void
     * @throws \Exception
     */
    public function addPolicy(HttpPipelinePolicy $policy, string $position)
    {
        if ($position !== HttpPipelinePosition::PER_CALL &&
            $position !== HttpPipelinePosition::PER_RETRY &&
            $position !== HttpPipelinePosition::BEFORE_TRANSPORT) {
            throw new \Exception("Invalid value '$position' for parameter 'position'.");
        }

        $this->_policies[$position] = [$policy];
    }
}
