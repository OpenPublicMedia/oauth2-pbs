<?php

namespace OpenPublicMedia\OAuth2\Client\Provider;

class Apple extends Pbs
{
    /**
     * Get authorization url to begin OAuth flow.
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->domain . '/oauth2/social/login/apple';
    }
}
