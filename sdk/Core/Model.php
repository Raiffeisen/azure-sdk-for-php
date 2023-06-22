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
     * Constructor.
     *
     * Initializes the object with the given configuration `$config`.
     *
     * If this method is overridden in a child class, it is recommended that
     *
     * - the last parameter of the constructor is a configuration array, like `$config` here.
     * - call the parent implementation at the end of the constructor.
     *
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            foreach ($config as $name => $value) {
                $this->$name = $value;
            }
        }
    }

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
    public function __set(string $name, mixed $value)
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
    public function getIterator(): \Traversable
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
    public function offsetGet($offset): mixed
    {
        return $this->_attributes[$offset] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->_attributes[$offset] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        unset($this->_attributes[$offset]);
    }

    /**
     * Parses a date time string to a \DateTime object
     * @param string $value
     * @return \DateTime|false
     * @throws \Exception
     */
    public function parseDateTime(string $value): \DateTime|false
    {
        $value = \DateTime::createFromFormat("Y-m-d\TH:i:s\Z", $value, new \DateTimeZone('UTC'));
        $value->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        return $value;
    }
}
