<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core\Pipeline;

use GuzzleHttp\RequestOptions;

class HttpPipeline extends \GuzzleHttp\Client
{
    /**
     * @var HttpPipelinePolicy[]
     */
    private $_pipeline = [];

    /**
     * @param mixed $transport
     * @param HttpPipelinePolicy[] $policies
     * @param mixed $responseClassifier
     */
    public function __construct($transport = null, array $policies = [], $responseClassifier = null)
    {
        foreach ($policies as $k => $policy) {
            if (!($policy instanceof HttpPipelinePolicy)) {
                unset($policies[$k]);
            }
        }

        $this->_pipeline = $policies;

        parent::__construct([]);
    }

    /**
     * {inheritDoc}
     */
    public function sendAsync(\Psr\Http\Message\RequestInterface $request, array $options = []): \GuzzleHttp\Promise\PromiseInterface
    {
        if (!empty($this->_pipeline)) {
            /** @var \GuzzleHttp\Psr7\Request $request */
            if (isset($options[RequestOptions::SYNCHRONOUS]) && $options[RequestOptions::SYNCHRONOUS]) {
                $this->_pipeline[0]->process($request, array_slice($this->_pipeline, 1));
            } else {
                $this->_pipeline[0]->processAsync($request, array_slice($this->_pipeline, 1));
            }
        }

        return parent::sendAsync($request, $options);
    }
}
