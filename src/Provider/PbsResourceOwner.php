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
        return $this->getValueByKey($this->response, 'pid');
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
     * Get resource owner first name.
     *
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->getValueByKey($this->response, 'first_name');
    }

    /**
     * Get resource owner last name.
     *
     * @return string|null
     */
    public function getLastName()
    {
        return $this->getValueByKey($this->response, 'last_name');
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
     * Get resource owner analytics ID.
     *
     * @return string|null
     */
    public function getAnalyticsId()
    {
        return $this->getValueByKey($this->response, 'analytics_id');
    }

    /**
     * Get resource owner thumbnail URL.
     *
     * @return string|null
     */
    public function getThumbnailUrl()
    {
        return $this->getValueByKey($this->response, 'thumbnail_URL');
    }

    /**
     * Get resource owner VPPA status.
     *
     * @link https://docs.pbs.org/display/uua/VPPA
     * @link https://docs.pbs.org/display/uua/VPPA+Developer+Guide
     * @return string|null
     */
    public function getVppaStatus()
    {
        $vppa_status = 'unknown';
        $vppa_accepted = $this->getValueByKey($this->response, 'vppa.vppa_accepted');
        $vppa_last_updated = $this->getValueByKey($this->response, 'vppa.vppa_last_updated');

        if (!$vppa_accepted) {
            $vppa_status = 'rejected';
        } elseif (!empty($vppa_last_updated)) {
            $vppa_status = 'valid';

            if (strtotime($vppa_last_updated) < strtotime('-2 years')) {
                $vppa_status = 'expired';
            }
        }

        return $vppa_status;
    }

    /**
     * Get resource owner zip code.
     *
     * @return string|null
     */
    public function getZipCode()
    {
        return $this->getValueByKey($this->response, 'zip_code');
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
