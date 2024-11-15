<?php

namespace OpenPublicMedia\OAuth2\Client\Provider;

use OpenPublicMedia\OAuth2\Client\Provider\Exception\PbsIdentityProviderException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Pbs extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Domain.
     *
     * @var string
     */
    public $domain = 'https://login.publicmediasignin.org';

    /**
     * Customer ID.
     *
     * @var string
     */
    protected $customerId;

    /**
     * Check a provider response for errors.
     *
     * @throws PbsIdentityProviderException
     * @param  ResponseInterface $response
     * @param  array $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw PbsIdentityProviderException::clientException($response, $data);
        } elseif (isset($data['error'])) {
            throw PbsIdentityProviderException::oauthException($response, $data);
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     * @return PbsResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new PbsResourceOwner($response);
    }

    /**
     * Get authorization url to begin OAuth flow.
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->domain . '/' . $this->customerId . '/login/authorize';
    }

    /**
     * {@inheritDoc}
     */
    protected function getAuthorizationParameters(array $options)
    {
        $options = parent::getAuthorizationParameters($options);
        $options['backend'] = $options['backend'] ?? 'traditional';
        $options['response_mode'] = $options['response_mode'] ?? 'query';
        $options['show_social_signin'] = $options['show_social_signin'] ?? 'off';
        return $options;
    }

    /**
     * Get access token url to retrieve token.
     *
     * @param  array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->domain . '/' . $this->customerId . '/login/token';
    }

    /**
     * Gets customer ID.
     *
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

        /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return [
            'openid',
            'profile',
            'email',
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getPkceMethod()
    {
        return self::PKCE_METHOD_S256;
    }

    /**
     * Get provider url to fetch user details.
     *
     * @param  AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->domain . '/' . $this->customerId . '/profiles/oidc/userinfo';
    }

    /**
     * {@inheritDoc}
     */
    protected function getScopeSeparator()
    {
        return ' ';
    }

    /**
     * Sets customer ID.
     *
     * @param string $host
     *
     * @return self
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }
}
