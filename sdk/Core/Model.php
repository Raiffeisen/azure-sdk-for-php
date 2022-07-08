<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core;

/**
 * Base model class for all other models
 */
class Model implements \IteratorAggregate, \ArrayAccess
{
    /**
     * @var array Holds the attributes
     */
    private array $_attributes = [];

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $name)
    {
        if (isset($this->_attributes[$name])) {
            return $this->_attributes[$name];
        }
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }

        throw new \Exception("Attribute '$name' does not exist.");
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            call_user_func([$this, $method], $value);
        }

        $this->_attributes[$name] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_attributes);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return isset($this->_attributes[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->_attributes[$offset] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->_attributes[$offset] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->_attributes[$offset]);
    }
}
