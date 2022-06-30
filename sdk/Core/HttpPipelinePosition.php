<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core;

/**
 * Represents a position of the policy in the pipeline.
 */
class HttpPipelinePosition
{
    public const PER_CALL = 'PerCall';
    public const PER_RETRY = 'PerRetry';
    public const BEFORE_TRANSPORT = 'BeforeTransport';
}
