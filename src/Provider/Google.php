<?php

namespace OpenPublicMedia\OAuth2\Client\Provider;

class Google extends Pbs
{
    /**
     * {@inheritDoc}
     */
    protected function getAuthorizationParameters(array $options)
    {
        return parent::getAuthorizationParameters($options + [
            'backend' => 'google-oauth2',
            'prompt' => 'login',
            'show_social_signin' => 'on',
        ]);
    }
}
