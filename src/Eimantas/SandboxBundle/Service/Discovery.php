<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.2
 * Time: 13.06
 */

namespace Eimantas\SandboxBundle\Service;


class Discovery
{
    private $engine;

    private $hull;

    private $fuelLoad;

    private $crew;

    /**
     * @return mixed
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param mixed $engine
     * @return Discovery
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHull()
    {
        return $this->hull;
    }

    /**
     * @param mixed $hull
     * @return Discovery
     */
    public function setHull($hull)
    {
        $this->hull = $hull;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFuelLoad()
    {
        return $this->fuelLoad;
    }

    /**
     * @param mixed $fuelLoad
     * @return Discovery
     */
    public function setFuelLoad($fuelLoad)
    {
        $this->fuelLoad = $fuelLoad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCrew()
    {
        return $this->crew;
    }

    /**
     * @param mixed $crew
     * @return Discovery
     */
    public function setCrew($crew)
    {
        $this->crew = $crew;
        return $this;
    }

}