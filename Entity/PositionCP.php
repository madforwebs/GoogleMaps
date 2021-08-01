<?php

namespace MadForWebs\GoogleMapsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\MappedSuperclass
 */
class PositionCP
{
    /**
     * @ORM\ManyToOne(targetEntity="Country")
     */
    protected $country;

    /**
     * @var int
     *
     * @ORM\Column(name="cp", type="integer")
     */
    protected $cp;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float")
     */
    protected $lng;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float")
     */
    protected $lat;

    /**
     * @ORM\Column(name="address", type="string")
     */
    protected $address = '';


    /**
     * @ORM\Column(type="string")
     */
    protected $province = '';

    /**
     * @ORM\Column(type="string")
     */
    protected $city = '';

    /**
     * @ORM\Column(type="string")
     */
    protected $zipCode = '';

    public function __toString()
    {
        return str_replace(',', ' ', $this->getAddress());

        return $this->lat.','.$this->lng;
    }

    /**
     * @param int $cp
     *
     * @return PositionCP
     */
    public function setCp($cp)
    {
        $this->cp = $cp;
    }

    /**
     * @return int
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * @param float $lng
     *
     * @return PositionCP
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    /**
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param float $lat
     *
     * @return PositionCP
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param mixed $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param mixed $zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

}
