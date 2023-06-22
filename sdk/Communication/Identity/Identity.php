<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication\Identity;

use Azure\Core\Model;

/**
 * A communication identity.
 *
 * @property string $id Identifier of the identity.
 */
class Identity extends Model
{
    /**
     * Initialize a new communication identity.
     * @param string $id Identifier of the identity.
     */
    public function __construct(string $id, array $config = [])
    {
        $this->id = $id;
        parent::__construct($config);
    }
}
