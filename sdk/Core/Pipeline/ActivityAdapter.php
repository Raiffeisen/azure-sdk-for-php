<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core\Pipeline;

class ActivityAdapter
{
    private $_activitySource;
    private $_diagnosticSource;
    private $_activityName;
    private $_kind;
    private $_diagnosticSourceArgs;
    private $_currentActivity;
    private $_tagCollection;
    private $_startTime;
    private $_links;

    public function __construct($activitySource, $diagnosticSource, string $activityName, ActivityKind $kind, $diagnosticSourceArgs)
    {
        $this->_activitySource = $activitySource;
        $this->_diagnosticSource = $diagnosticSource;
        $this->_activityName = $activityName;
        $this->_kind = $kind;
        $this->_diagnosticSourceArgs = $diagnosticSourceArgs;

        switch ($this->_kind) {
            case ActivityKind::INTERNAL:
                $this->addTag('kind', 'internal');
                break;
            case ActivityKind::SERVER:
                $this->addTag('kind', 'server');
                break;
            case ActivityKind::CLIENT:
                $this->addTag('kind', 'client');
                break;
            case ActivityKind::PRODUCER:
                $this->addTag('kind', 'producer');
                break;
            case ActivityKind::CONSUMER:
                $this->addTag('kind', 'consumer');
                break;
        }
    }

    public function addTag(string $name, string $value)
    {
        if ($this->_currentActivity === null) {
//            $this->_tagCollection
        }
    }
}
