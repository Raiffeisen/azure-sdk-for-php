<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Chat;

use Azure\Communication\CommunicationTokenCredential;
use GuzzleHttp\Psr7\Uri;

/**
 * The Azure Communication Services Chat client.
 */
class ChatClient
{
    private $_chatRestClient;
    private $_endpointUri;
    private $_communicationTokenCredential;
    private $_chatClientOptions;

    public function __construct(Uri $endpoint, CommunicationTokenCredential $communicationTokenCredential, ChatClientOptions $options = null)
    {
        if (!$options) {
            ChatClientOptions::default();
        }
        $this->_chatClientOptions = $options;
        $this->_communicationTokenCredential = $communicationTokenCredential;
        $this->_endpointUri = $endpoint;
        $pipeline = $options;
    }
}
