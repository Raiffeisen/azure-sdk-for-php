<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core;

/**
 * Represents a credential capable of providing an OAuth token.
 */
abstract class TokenCredential
{
    /**
     * Get an AccessToken for the specified set of scopes.
     * @param TokenRequestContext $requestContext The TokenRequestContext with authentication information.
     * @param mixed $cancellationToken The CancellationToken to use.
     * @return AccessToken
     */
    public abstract function getTokenAsync(TokenRequestContext $requestContext, $cancellationToken = null): AccessToken;

    /**
     * Get an AccessToken for the specified set of scopes.
     * @param TokenRequestContext $requestContext The TokenRequestContext with authentication information.
     * @param mixed $cancellationToken The CancellationToken to use.
     * @return AccessToken A valid AccessToken.
     */
    public abstract function getToken(TokenRequestContext $requestContext, $cancellationToken = null): AccessToken;
}
