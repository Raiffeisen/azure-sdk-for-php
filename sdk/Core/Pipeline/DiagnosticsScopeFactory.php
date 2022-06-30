<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core\Pipeline;

class DiagnosticsScopeFactory
{
    private static $_listeners;
    private $_resourceProviderNamespace;
    private $_source;
    private $_suppressNestedClientActivities;
    public $isActivityEnabled;

    public function __construct(string $clientNamespace, string $resourceProviderNamespace, bool $isActivityEnabled, bool $suppressNestedClientActivities)
    {
        $this->_resourceProviderNamespace = $resourceProviderNamespace;
        $this->isActivityEnabled = $isActivityEnabled;
        $this->_suppressNestedClientActivities = $suppressNestedClientActivities;

        if ($this->isActivityEnabled) {
            self::$_listeners[$clientNamespace] = null; //TODO
        }
    }

    
}
