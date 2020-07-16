<?php

namespace OpenPublicMedia\OAuth2\Client\Test\Provider;

use OpenPublicMedia\OAuth2\Client\Provider\Apple;

class AppleTest extends PbsTest
{
    protected function setUp(): void
    {
        $this->provider = new Apple([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
        ]);
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertEquals('/oauth2/social/login/apple', $uri['path']);
    }
}
