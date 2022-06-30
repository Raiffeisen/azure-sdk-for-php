<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core\Pipeline;

use Azure\Core\TokenCredential;
use Azure\Core\TokenRequestContext;

class AccessTokenCache
{
    private $_credential;
    private $_syncObj = [];
    private $_tokenCredential;
    private $_tokenRefreshOffset;
    private $_tokenRefreshRetryDelay;
    private $_state;

    public function __construct(TokenCredential $credential, \DateInterval $tokenRefreshOffset, \DateInterval $tokenRefreshRetryDelay)
    {
        $this->_credential = $credential;
        $this->_tokenRefreshOffset = $tokenRefreshOffset;
        $this->_tokenRefreshRetryDelay = $tokenRefreshRetryDelay;
    }

    public function getHeaderValueAsync(\GuzzleHttp\Psr7\Request $message, TokenRequestContext $context)
    {
        $getTokenFromCredential = false;
        $headerValueTcs = null;
        $backgroundUpdateTcs = null;
        $maxCancellationRetries = 3;

        while (true) {
            [$headerValueTcs, $backgroundUpdateTcs, $getTokenFromCredential] = $this->getTaskCompletionSource($context);
        }
    }

    private function getTaskCompletionSource(TokenRequestContext $context): array
    {
        $localState = $this->_state;
        if ($localState !== null && $localState) {

        }
    }
}
