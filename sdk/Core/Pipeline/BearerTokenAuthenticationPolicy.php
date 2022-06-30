<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core\Pipeline;

use Azure\Core\TokenCredential;
use Azure\Core\TokenRequestContext;

class BearerTokenAuthenticationPolicy extends HttpPipelinePolicy
{
    private $_scopes = [];
    /**
     * @var AccessTokenCache
     */
    private $_accessTokenCache;

    public function __construct(TokenCredential $credential, array $scopes)
    {
        $tokenRefreshOffset = \DateInterval::createFromDateString('5 minutes');
        $tokenRefreshRetryDelay = \DateInterval::createFromDateString('30 seconds');
        $this->_scopes = $scopes;
        $this->_accessTokenCache = new AccessTokenCache($credential, $tokenRefreshOffset, $tokenRefreshRetryDelay);
    }

    /**
     * {@inheritDoc}
     */
    public function processAsync(\GuzzleHttp\Psr7\Request $message, array $pipeline): \GuzzleHttp\Promise\PromiseInterface
    {
        if ($message->getUri()->getScheme() !== 'https') {
            throw new \Exception('Bearer token authentication is not permitted for non TLS protected (https) endpoints.');
        }


    }

    /**
     * {@inheritDoc}
     */
    public function process(\GuzzleHttp\Psr7\Request $message, array $pipeline)
    {
        if ($message->getUri()->getScheme() !== 'https') {
            throw new \Exception('Bearer token authentication is not permitted for non TLS protected (https) endpoints.');
        }
    }

    protected function authorizeRequest(\GuzzleHttp\Psr7\Request $message)
    {
        $context = new TokenRequestContext($this->_scopes);
        $this->authenticateAndAuthorizeRequest($message, $context);
    }

    protected function authenticateAndAuthorizeRequest(\GuzzleHttp\Psr7\Request $message, TokenRequestContext $context) {
        $headerValue = $this->_accessTokenCache;
        $message->withHeader('Authorization', $headerValue);
    }
}
