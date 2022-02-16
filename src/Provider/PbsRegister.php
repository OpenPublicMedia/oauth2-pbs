<?php

namespace OpenPublicMedia\OAuth2\Client\Provider;

class PbsRegister extends Pbs
{
    /**
     * Get authorization url to begin OAuth flow for account registration.
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->domain . '/oauth2/register';
    }
}
