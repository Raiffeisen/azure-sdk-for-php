<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication;

class CommunicationUserIdentifier extends CommunicationIdentifier
{
    /**
     * @var string The id of the communication user.
     */
    public $id;

    /**
     * Initializes a new instance of CommunicationUserIdentifier.
     * @param string $id Id of the communication user.
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * Compare with another instance.
     * @param CommunicationUserIdentifier $other
     * @return bool
     */
    function equals(CommunicationIdentifier $other): bool
    {
        return $this->id === $other->id;
    }

    /**
     * The id of the communication user.
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
