<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core\Pipeline;

/**
 * Represent an extension point for the HttpPipeline that can mutate the Request and react to received Response.
 */
abstract class HttpPipelinePolicy
{
    /**
     * Applies the policy to the message. Implementers are expected to mutate the request before calling processNextAsync
     * and observe the Http.Response changes after.
     * @param \GuzzleHttp\Psr7\Request $message The HttpMessage this policy would be applied to
     * @param HttpPipelinePolicy[] $pipeline The set of HttpPipelinePolicy to execute after current one.
     * @return \GuzzleHttp\Promise\PromiseInterface The ValueTask representing the asynchronous operation.
     */
    public abstract function processAsync(\GuzzleHttp\Psr7\Request $message, array $pipeline): \GuzzleHttp\Promise\PromiseInterface;

    /**
     * Applies the policy to the message. Implementers are expected to mutate the request before calling processNextAsync
     * and observe the Http.Response changes after.
     * @param \GuzzleHttp\Psr7\Request $message The HttpMessage this policy would be applied to
     * @param HttpPipelinePolicy[] $pipeline The set of HttpPipelinePolicy to execute after current one.
     * @return void
     */
    public abstract function process(\GuzzleHttp\Psr7\Request $message, array $pipeline);

    /**
     * Invokes the next HttpPipelinePolicy in the pipeline.
     * @param \GuzzleHttp\Psr7\Request $message The HttpMessage next policy would be applied to.
     * @param HttpPipelinePolicy[] $pipeline The set of HttpPipelinePolicy to execute after next one.
     * @return \GuzzleHttp\Promise\PromiseInterface The ValueTask representing the asynchronous operation.
     */
    public static function processNextAsync(\GuzzleHttp\Psr7\Request $message, array $pipeline): \GuzzleHttp\Promise\PromiseInterface
    {
        return $pipeline[0]->processAsync($message, array_slice($pipeline, 1));
    }

    /**
     * Invokes the next HttpPipelinePolicy in the pipeline.
     * @param \GuzzleHttp\Psr7\Request $message The HttpMessage next policy would be applied to.
     * @param HttpPipelinePolicy[] $pipeline The set of HttpPipelinePolicy to execute after next one.
     * @return void
     */
    public static function processNext(\GuzzleHttp\Psr7\Request $message, array $pipeline)
    {
        $pipeline[0]->process($message, array_slice($pipeline, 1));
    }
}
