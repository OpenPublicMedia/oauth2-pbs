<?php

namespace OpenPublicMedia\OAuth2\Client\Provider;

class Facebook extends Pbs
{
    /**
     * {@inheritDoc}
     */
    protected function getAuthorizationParameters(array $options)
    {
        return parent::getAuthorizationParameters($options + [
            'backend' => 'facebook',
            'prompt' => 'login',
            'show_social_signin' => 'on',
        ]);
    }
}
