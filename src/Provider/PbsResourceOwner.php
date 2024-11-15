<?php

namespace OpenPublicMedia\OAuth2\Client\Provider;

use League\OAuth2\Client\Tool\ArrayAccessorTrait;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class PbsResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Raw response.
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array  $response
     */
    public function __construct(array $response = array())
    {
        $this->response = $response;
    }

    /**
     * Get resource owner ID.
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, 'sub');
    }

    /**
     * Get resource owner email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getValueByKey($this->response, 'email');
    }

    /**
     * Whether the resource owner email has been verified.
     *
     * @return boolean
     */
    public function isEmailVerified()
    {
        return $this->getValueByKey($this->response, 'email_verified', false);
    }

    /**
     * Get resource owner first name.
     *
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->getValueByKey($this->response, 'given_name');
    }

    /**
     * Get resource owner last name.
     *
     * @return string|null
     */
    public function getLastName()
    {
        return $this->getValueByKey($this->response, 'family_name');
    }

    /**
     * Get resource owner full name.
     *
     * @return string|null
     */
    public function getName()
    {
        $first_name = $this->getFirstName();
        $last_name = $this->getLastName();

        $name = '';
        if (!empty($first_name) && !empty($last_name)) {
            $name = $first_name . ' ' . $last_name;
        } elseif (!empty($last_name)) {
            $name = $last_name;
        } elseif (!empty($first_name)) {
            $name = $first_name;
        }

        return $name;
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
