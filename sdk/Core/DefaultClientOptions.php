<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core;

use GuzzleHttp\Client;

class DefaultClientOptions extends ClientOptions
{
    public function __construct()
    {
        $this->_transport = new Client();
    }
}
