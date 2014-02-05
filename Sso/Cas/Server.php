<?php

namespace BeSimple\SsoAuthBundle\Sso\Cas;

use BeSimple\SsoAuthBundle\Sso\AbstractServer;
use BeSimple\SsoAuthBundle\Sso\ServerInterface;
use Buzz\Message\Request;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Server extends AbstractServer
{
    /**
     * @return string
     */
    public function getLoginUrl()
    {
        return sprintf('%s?service=%s', parent::getLoginUrl(), urlencode($this->getCheckUrl()));
    }

    /**
     * @return string
     */
    public function getLogoutUrl(HttpRequest $request = null)
    {
        $service = sprintf('service=%s', urlencode($this->getCheckUrl()));
        if($this->getLogoutTarget()) {
            if($request) {
                $url = sprintf('&url=%s', urlencode($request->getUriForPath($this->getLogoutTarget())));
            } else {
                $url = sprintf('&url=%s', urlencode($this->getLogoutTarget()));
            }
        } else {
            $url = null;
        }

        return sprintf('%s?%s%s', parent::getLogoutUrl(), $service, $url);
    }

    /**
     * @return string
     */
    public function getValidationUrl()
    {
        return sprintf('%s?service=%s', parent::getValidationUrl(), urlencode($this->getCheckUrl()));
    }

    /**
     * @param string $credentials
     *
     * @return \Buzz\Message\Request
     */
    public function buildValidationRequest($credentials)
    {
        $request = new Request();
        $request->fromUrl(sprintf(
            '%s&ticket=%s',
            $this->getValidationUrl(),
            $credentials
        ));

        return $request;
    }

}
