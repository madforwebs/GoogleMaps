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
     * @var int
     *
     * @ORM\Column(name="cp", type="integer")
     */
    private $cp;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float")
     */
    private $lng;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float")
     */
    private $lat;

    /**
     * @ORM\Column(name="address", type="string")
     */
    private $address = '';

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
}
