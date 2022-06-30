<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity\Models;

class CommunicationTokenScope extends RequestObject
{
    const CHAT_VALUE = 'chat';
    const VOIP_VALUE = 'voip';

    /**
     * @var string
     */
    private $_value;

    /**
     * Initializes a new instance of CommunicationTokenScope.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->_value = $value;
    }

    /**
     * chat.
     * @return CommunicationTokenScope
     */
    public static function chat(): CommunicationTokenScope
    {
        return new self(self::CHAT_VALUE);
    }

    /**
     * voip.
     * @return CommunicationTokenScope
     */
    public static function voip(): CommunicationTokenScope
    {
        return new self(self::VOIP_VALUE);
    }

    /**
     * Get the value.
     * @return string
     */
    public function __toString()
    {
        return $this->_value;
    }
}
