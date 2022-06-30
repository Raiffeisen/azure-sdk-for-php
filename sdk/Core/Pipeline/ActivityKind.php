<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core\Pipeline;

class ActivityKind
{
    const INTERNAL = 0;
    const SERVER = 1;
    const CLIENT = 2;
    const PRODUCER = 3;
    const CONSUMER = 4;
}
