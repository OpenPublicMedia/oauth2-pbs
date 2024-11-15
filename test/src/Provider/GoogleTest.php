<?php

namespace OpenPublicMedia\OAuth2\Client\Test\Provider;

use OpenPublicMedia\OAuth2\Client\Provider\Google;

class GoogleTest extends PbsTest
{
    protected function setUp(): void
    {
        $this->provider = new Google([
            'clientId' => 'mock_client_id',
            'customerId' => 'mock_customer_id',
            'redirectUri' => 'none',
        ]);
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertEquals('/mock_customer_id/login/authorize', $uri['path']);

        $this->assertArrayHasKey('backend', $query);
        $this->assertArrayHasKey('prompt', $query);
        $this->assertArrayHasKey('show_social_signin', $query);

        $this->assertEquals('google-oauth2', $query['backend']);
        $this->assertEquals('login', $query['prompt']);
        $this->assertEquals('on', $query['show_social_signin']);
    }
}
