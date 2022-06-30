<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication;

abstract class CommunicationIdentifier
{
    /**
     * Compare with another instance.
     * @param CommunicationIdentifier $other
     * @return bool
     */
    abstract function equals(CommunicationIdentifier $other): bool;
}
