<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core\Pipeline;

use Azure\Core\TokenRequestContext;

class TokenRequestState
{
    private $_currentContext;
    private $_infoTcs;
    private $_backgroundUpdateTcs;

    public function __construct(TokenRequestContext $currentContext)
    {
        $this->_currentContext = $currentContext;
    }
}
