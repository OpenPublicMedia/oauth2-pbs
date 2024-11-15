<?php

namespace OpenPublicMedia\OAuth2\Client\Provider;

class Apple extends Pbs
{
    /**
     * {@inheritDoc}
     */
    protected function getAuthorizationParameters(array $options)
    {
        return parent::getAuthorizationParameters($options + [
            'backend' => 'apple',
            'prompt' => 'login',
            'show_social_signin' => 'on',
        ]);
    }
}
