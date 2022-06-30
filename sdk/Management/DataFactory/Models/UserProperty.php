<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Management\DataFactory\Models;

abstract class UserProperty
{
    private $_name;
    private $_value;

    /**
     * Initializes a new instance of the UserProperty class.
     * @param string|null $name User property name.
     * @param mixed $value User property value. Type: string (or expression with resultType string).
     */
    public function __construct(string $name = null, $value = null)
    {
        if ($name) {
            $this->_name = $name;
            $this->_value = $value;
        }

        $this->customInit();
    }

    /**
     * An initialization method that performs custom operations like setting defaults.
     * @return void
     */
    abstract public function customInit(): void;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->_name = $name;
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * @param mixed|null $value
     */
    public function setValue($value): void
    {
        $this->_value = $value;
    }

    /**
     * Validate the object
     * @return void
     * @throws \Exception thrown if validation fails
     */
    public function validate()
    {
        if ($this->_name === null) {
            throw new \Exception('Property `name` cannot be null.');
        }
        if ($this->_value === null) {
            throw new \Exception('Property `value` cannot be null.');
        }
    }
}
