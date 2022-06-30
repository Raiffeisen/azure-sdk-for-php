<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity\Models;

class CommunicationIdentityCreateRequest extends RequestObject implements \ArrayAccess
{
    /**
     * @var CommunicationTokenScope[]
     */
    private $_createTokenWithScopes = [];

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param int|string $offset <p>
     * An offset to check for.
     * </p>
     * @return bool true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset): bool
    {
        return isset($this->_createTokenWithScopes[$offset]);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param int|string $offset <p>
     * The offset to retrieve.
     * </p>
     * @return CommunicationTokenScope Can return all value types.
     */
    public function offsetGet($offset): CommunicationTokenScope
    {
        return $this->_createTokenWithScopes[$offset];
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param int|string $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param CommunicationTokenScope $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof CommunicationTokenScope)) {
            throw new \InvalidArgumentException();
        }

        $this->_createTokenWithScopes[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param int|string $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->_createTokenWithScopes[$offset]);
        }
    }

    /**
     * @return CommunicationTokenScope[]
     */
    public function getCreateTokenWithScopes(): array
    {
        return $this->_createTokenWithScopes;
    }
}
