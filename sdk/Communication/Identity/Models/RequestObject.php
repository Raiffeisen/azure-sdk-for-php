<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity\Models;

class RequestObject
{
    /**
     * Serializes properties to array.
     * @return array
     */
    public function toArray(): array
    {
        $data = [];
        $r = new \ReflectionClass($this);

        foreach ($r->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
            $name = ltrim($property->getName(), '_');
            $method = 'get' . ucfirst($name);
            $data[$name] = call_user_func([$this, $method]);
        }

        return $data;
    }
}
