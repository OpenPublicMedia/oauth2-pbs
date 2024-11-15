<?php

namespace OpenPublicMedia\OAuth2\Client\Test\Provider;

use GuzzleHttp\Psr7\Utils;
use League\OAuth2\Client\Tool\QueryBuilderTrait;
use Mockery as m;
use OpenPublicMedia\OAuth2\Client\Provider\Pbs;
use PHPUnit\Framework\TestCase;

class PbsTest extends TestCase
{
    use QueryBuilderTrait;

    /**
     * @var Pbs
     */
    protected $provider;

    protected function setUp(): void
    {
        $this->provider = new Pbs([
            'clientId' => 'mock_client_id',
            'customerId' => 'mock_customer_id',
            'redirectUri' => 'none',
        ]);
    }

    public function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertEquals('/mock_customer_id/login/authorize', $uri['path']);

        $this->assertArrayHasKey('backend', $query);
        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('code_challenge_method', $query);
        $this->assertArrayHasKey('code_challenge', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('response_mode', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('scope', $query);
        $this->assertArrayHasKey('show_social_signin', $query);
        $this->assertArrayHasKey('state', $query);
        
        $this->assertNotNull($this->provider->getState());

        $this->assertEquals('traditional', $query['backend']);
        $this->assertEquals('off', $query['show_social_signin']);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];

        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);

        $this->assertEquals('/mock_customer_id/login/token', $uri['path']);
    }

    public function testGetAccessToken()
    {
        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')
            ->andReturn(Utils::streamFor(json_encode([
                'token_type' => 'Bearer',
                'scope' => 'openid profile email offline_access',
                'id_token' => 'mock_id_token',
                'access_token' => 'mock_access_token',
                'refresh_token' => 'mock_refresh_token',
                'expires' => 1731649207,
              ])));
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getStatusCode')->andReturn(200);

        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertEquals('mock_refresh_token', $token->getRefreshToken());
        $this->assertEquals(1731649207, $token->getExpires());
        $this->assertEquals([
            'token_type' => 'Bearer',
            'scope' => 'openid profile email offline_access',
            'id_token' => 'mock_id_token',
        ], $token->getValues());
    }
}
