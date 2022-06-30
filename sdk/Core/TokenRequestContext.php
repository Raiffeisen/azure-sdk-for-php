<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core;

class TokenRequestContext
{
    private $_scopes = [];
    private $_parentRequestId;
    private $_claims;
    private $_tenantId;

    /**
     * Creates a new TokenRequest with the specified scopes.
     * @param array $scopes The scopes required for the token.
     * @param string|null $parentRequestId The Request.ClientRequestId of the request requiring a token for authentication, if applicable.
     * @param string|null $claims Additional claims to be included in the token.
     * @param string|null $tenantId The tenantId to be included in the token request.
     */
    public function __construct(array $scopes, ?string $parentRequestId = null, ?string $claims = null, ?string $tenantId = null)
    {
        $this->_scopes = $scopes;
        $this->_parentRequestId = $parentRequestId;
        $this->_claims = $claims;
        $this->_tenantId = $tenantId;
    }

    /**
     * The scopes required for the token.
     * @return array
     */
    public function getScopes(): array
    {
        return $this->_scopes;
    }

    /**
     * The Request.ClientRequestId of the request requiring a token for authentication, if applicable.
     * @return string|null
     */
    public function getParentRequestId(): ?string
    {
        return $this->_parentRequestId;
    }

    /**
     * Additional claims to be included in the token. See https://openid.net/specs/openid-connect-core-1_0-final.html#ClaimsParameter
     * for more information on format and content.
     * @return string|null
     * @see https://openid.net/specs/openid-connect-core-1_0-final.html#ClaimsParameter
     */
    public function getClaims(): ?string
    {
        return $this->_claims;
    }

    /**
     * The tenantId to be included in the token request.
     * @return string|null
     */
    public function getTenantId(): ?string
    {
        return $this->_tenantId;
    }
}
