<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Management\DataFactory\Models;

abstract class Activity
{
    /**
     * @var string
     */
    private $_name;
    /**
     * @var array
     */
    private $_additionalProperties;
    /**
     * @var string
     */
    private $_description;
    /**
     * @var ActivityDependency[]
     */
    private $_dependsOn;
    /**
     * @var UserProperty[]
     */
    private $_userProperties;

    public function __construct(string $name = null, array $additionalProperties = [], string $description = '', array $dependsOn = [], array $userProperties = [])
    {
        if ($name !== null) {
            $this->_name = $name;
            $this->_additionalProperties = $additionalProperties;
            $this->_description = $description;
            $this->_dependsOn = $dependsOn;
            $this->_userProperties = $userProperties;
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
     * @return array
     */
    public function getAdditionalProperties(): array
    {
        return $this->_additionalProperties;
    }

    /**
     * @param array $additionalProperties
     */
    public function setAdditionalProperties(array $additionalProperties): void
    {
        $this->_additionalProperties = $additionalProperties;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->_description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->_description = $description;
    }

    /**
     * @return ActivityDependency[]
     */
    public function getDependsOn(): array
    {
        return $this->_dependsOn;
    }

    /**
     * @param ActivityDependency[] $dependsOn
     */
    public function setDependsOn(array $dependsOn): void
    {
        $this->_dependsOn = $dependsOn;
    }

    /**
     * @return UserProperty[]
     */
    public function getUserProperties(): array
    {
        return $this->_userProperties;
    }

    /**
     * @param UserProperty[] $userProperties
     */
    public function setUserProperties(array $userProperties): void
    {
        $this->_userProperties = $userProperties;
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
        if ($this->_dependsOn !== null) {
            foreach ($this->_dependsOn as $element) {
                if ($element !== null) {
                    $element->validate();
                }
            }
        }
        if ($this->_userProperties !== null) {
            foreach ($this->_userProperties as $element) {
                if ($element !== null) {
                    $element->validate();
                }
            }
        }
    }
}
