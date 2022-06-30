<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Management\DataFactory\Models;

abstract class ActivityDependency
{
    private $_activity;
    private $_additionalProperties;
    private $_dependencyConditions;

    /**
     * Initializes a new instance of the ActivityDependency class.
     * @param string $activity Activity name.
     * @param array $dependencyConditions Match-Condition for the dependency.
     * @param array $additionalProperties Unmatched properties from the message are deserialized this collection.
     */
    public function __construct(string $activity, array $dependencyConditions, array $additionalProperties = [])
    {
        $this->_activity = $activity;
        $this->_additionalProperties = $additionalProperties;
        $this->_dependencyConditions = $dependencyConditions;
        $this->customInit();
    }

    /**
     * An initialization method that performs custom operations like setting defaults
     * @return void
     */
    abstract public function customInit(): void;

    /**
     * @return string
     */
    public function getActivity(): string
    {
        return $this->_activity;
    }

    /**
     * @param string $activity
     */
    public function setActivity(string $activity): void
    {
        $this->_activity = $activity;
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
     * @return array
     */
    public function getDependencyConditions(): array
    {
        return $this->_dependencyConditions;
    }

    /**
     * @param array $dependencyConditions
     */
    public function setDependencyConditions(array $dependencyConditions): void
    {
        $this->_dependencyConditions = $dependencyConditions;
    }

    /**
     * Validate the object
     * @return void
     * @throws \Exception thrown if validation fails
     */
    public function validate()
    {
        if ($this->_activity === null) {
            throw new \Exception('Property `activity` cannot be null.');
        }
        if ($this->_dependencyConditions === null) {
            throw new \Exception('Property `dependencyConditions` cannot be null.');
        }
    }
}
