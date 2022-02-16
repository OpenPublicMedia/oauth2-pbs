<?php

namespace OpenPublicMedia\OAuth2\Client\Test\Provider;

use OpenPublicMedia\OAuth2\Client\Provider\PbsRegister;

class PbsRegisterTest extends PbsTest
{
    protected function setUp(): void
    {
        $this->provider = new PbsRegister([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
        ]);
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertEquals('/oauth2/register', $uri['path']);
    }
}
