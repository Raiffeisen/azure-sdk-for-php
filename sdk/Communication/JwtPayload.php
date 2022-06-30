<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Communication;

class JwtPayload
{
    private $_exp;
    private $_acsScope;
    private $_expiresOn;
    private $_scopes = [];

    public function __construct($expiresOnRaw, $scopesRaw)
    {
        $this->setExpiresOnRaw($expiresOnRaw);
        $this->setScopesRaw($scopesRaw);
    }

    public function getExpiresOnRaw()
    {
        return $this->_exp;
    }

    public function setExpiresOnRaw($expiresOnRaw)
    {
        $this->_exp = $expiresOnRaw;
        $this->_expiresOn = \DateInterval::createFromDateString($expiresOnRaw);
    }

    public function getScopesRaw()
    {
        return $this->_acsScope;
    }

    public function setScopesRaw($scopesRaw)
    {
        $this->_acsScope = $scopesRaw;
        $this->_scopes = explode(',', $scopesRaw);
    }

    public function getExpiresOn(): \DateInterval
    {
        return $this->_expiresOn;
    }

    public function getScopes(): array
    {
        return $this->_scopes;
    }
}
