<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core\Pipeline;

class DiagnosticScope
{
    const AZURE_SDK_SCOPE_LABEL = 'az.sdk.scope';
    private static $azureSdkScopeValue = true;
    private static $activitySources = [];

    private $_activityAdapter;
}
