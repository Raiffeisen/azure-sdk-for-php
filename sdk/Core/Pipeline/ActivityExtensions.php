<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core\Pipeline;

use Azure\Management\DataFactory\Models\Activity;

class ActivityExtensions
{
    /**
     * @var callable
     */
    private static $_getCustomPropertyMethod;
    /**
     * @var callable
     */
    private static $_setCustomPropertyMethod;
    /**
     * @var callable
     */
    private static $_createTagsCollectionMethod;

    /**
     * @param Activity $activity
     * @param string $propertyName
     * @return array|null
     */
    public static function getCustomProperty(Activity $activity, string $propertyName): ?array
    {
        if (!isset(self::$_getCustomPropertyMethod)) {
            if (method_exists($activity, 'getCustomProperty')) {
                self::$_getCustomPropertyMethod = [$activity, 'getCustomProperty'];
            } else {
                self::$_getCustomPropertyMethod = function ($activity, $propertyName) {
                    return null;
                };
            }
        }

        return call_user_func(self::$_getCustomPropertyMethod, $activity, $propertyName);
    }

    /**
     * @param Activity $activity
     * @param string $propertyName
     * @param mixed $propertyValue
     * @return void
     */
    public static function setCustomProperty(Activity $activity, string $propertyName, $propertyValue)
    {
        if (!isset(self::$_setCustomPropertyMethod)) {
            if (method_exists($activity, 'setCustomProperty')) {
                self::$_setCustomPropertyMethod = [$activity, 'setCustomProperty'];
            } else {
                self::$_setCustomPropertyMethod = function ($activity, $propertyName, $propertyValue) {
                };
            }
        }

        call_user_func(self::$_setCustomPropertyMethod, $activity, $propertyName, $propertyValue);
    }

    public static function createTagsCollection()
    {
        if (!isset(self::$_createTagsCollectionMethod)) {

        }
    }
}
