<?php

namespace MadForWebs\GoogleMapsBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class Map
{
    protected $apiGoogleMaps;

    protected $positionEntity;

    protected $positionRepository;

    protected $em;

    protected $container;

    public function __construct(EntityManager $em, Container $container, $apiGoogleMaps, $positionEntity, $positionRepository)
    {
        $this->em = $em;
        $this->container = $container;
        $this->apiGoogleMaps = $apiGoogleMaps;
        $this->positionEntity = $positionEntity;
        $this->positionRepository = $positionRepository;
    }

    public function circle_distance($lat1, $lon1, $lat2, $lon2)
    {
        $rad = M_PI / 180;

        return acos(sin($lat2 * $rad) * sin($lat1 * $rad) + cos($lat2 * $rad) * cos($lat1 * $rad) * cos($lon2 * $rad - $lon1 * $rad)) * 6371; // Kilometers
    }

    public function DistAB($lata, $lona, $latb, $lonb)
    {
        $earth_radius = 6371000;
        $delta_lat = $latb - $lata;
        $delta_lon = $lonb - $lona;

        $a = pow(sin($delta_lat / 2), 2);
        $a += cos(deg2rad($lata)) * cos(deg2rad($latb)) * pow(sin(deg2rad($delta_lon / 29)), 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = 2 * $earth_radius * $c;
        $distance = round($distance, 4);

        return $distance;
    }

    public function getPositionFromPosition( $position)
    {
        $cp = $position->getZipCode();
        $country = $position->getCountry();
        $address = $position->getAddress();

        $positionCP = $this->em->getRepository($this->positionRepository)->findOneBy(array('cp' => $cp));

        if (!$positionCP) {
            $positionCP = $this->getPosition($address.','.$cp.','.$country);
            $this->em->persist($positionCP);
            $this->em->flush();
        }

        return $positionCP;
    }

    /**
     * @param PositionCP $positionA
     * @param PositionCP $positionB
     *
     * @return float
     */
    public function getDistanceBetween( $positionA,  $positionB)
    {
        $earthRadius = 6371000;
        $latFrom = deg2rad($positionA->getLat());
        $lonFrom = deg2rad($positionA->getLng());
        $latTo = deg2rad($positionB->getLat());
        $lonTo = deg2rad($positionB->getLng());

        $theta = $lonFrom - $lonTo;
        $dist = sin(deg2rad($latFrom)) * sin(deg2rad($latTo)) + cos(deg2rad($latFrom)) * cos(deg2rad($latTo)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $distance = ($miles * 1.609344);

        return $miles * 100;
    }

    public function getPositionFromAddress($address)
    {
        $positionCP = $this->em->getRepository($this->positionRepository)->findOneBy(array('address' => $address));
        if (!$positionCP) {
            $positionCP = $this->getPosition($address);
            $this->em->persist($positionCP);
            $this->em->flush();
        }

        return $positionCP;
    }

    public function getPosition($address)
    {
        $requestPosition = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address.',spain').'&sensor=false&key='.$this->apiGoogleMaps;
        $coords = json_decode(file_get_contents($requestPosition));

        if ($coords->status == 'ZERO_RESULTS') {
            $addressArray = explode(',', trim($address));
            $address = $addressArray[0].',';
            for ($i = count($addressArray) - 1; $i >= 0; --$i) {
                if ($addressArray[$i] != '') {
                    $address .= $addressArray[$i];
                    break;
                }
            }
            $requestPosition = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address.',spain').'&sensor=false&key='.$this->apiGoogleMaps;
            $coords = json_decode(file_get_contents($requestPosition));
        }

        $lng = $coords->results[0]->geometry->location->lng;
        $lat = $coords->results[0]->geometry->location->lat;

        $requestCoords = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&sensor=true_or_false&key='.$this->apiGoogleMaps;
        $coords = json_decode(file_get_contents($requestCoords));
        foreach ($coords->results[0]->address_components as $address_component) {
            if ($address_component->types[0] == 'postal_code') {
                $cp = $address_component->long_name;
            }elseif ($address_component->types[0] == 'country') {
                $country = $address_component->long_name;
            /*
            }elseif ($address_component->types[0] == 'administrative_area_level_1') {
                $area = $address_component->long_name;
            */
            }elseif ($address_component->types[0] == 'administrative_area_level_2') {
                $city = $address_component->long_name;

            }elseif ($address_component->types[0] == 'locality') {
                $localidad = $address_component->long_name;
            }elseif ($address_component->types[0] == 'route') {
                $localidad = $address_component->long_name;
            }
        }

        if(isset($coords->results[0]) && $coords->results[0]->formatted_address != null){
            $address = $coords->results[0]->formatted_address;
        }

        $positionCP = new $this->positionEntity();
        $positionCP->setCity($city);
        $positionCP->setProvince($localidad);
        $positionCP->setCountry($country);
        $positionCP->setCp($cp);
        $positionCP->setZipCode($cp);
        $positionCP->setLat($lat);
        $positionCP->setLng($lng);
        $positionCP->setAddress($address);

        return $positionCP;
    }
}
