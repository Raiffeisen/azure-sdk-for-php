<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity\Models;

class CommunicationIdentity
{
    /**
     * @var string Identifier of the identity
     */
    public $id;

    /**
     * Initializes a new instance of CommunicationIdentity.
     * @param string $id Identifier of the identity.
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * Create a CommunicationIdentity out of a json deserialized array.
     * @param array $element The deserialized json string.
     * @return self
     */
    public static function deserializeCommunicationIdentity(array $element): self
    {
        $id = null;
        foreach ($element as $name => $value) {
            if ($name === 'id') {
                $id = $value;
                break;
            }
        }

        return new self($id);
    }
}
