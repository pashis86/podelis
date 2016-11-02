<?php
/**
 * Created by PhpStorm.
 * User: sarunas
 * Date: 16.11.2
 * Time: 20.23
 */

namespace NFQ\NFQBundle\Service;


class Crane
{
    private $body;
    private $chassis;
    private $upperworks;

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     * @return Crane
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChassis()
    {
        return $this->chassis;
    }

    /**
     * @param mixed $chassis
     * @return Crane
     */
    public function setChassis($chassis)
    {
        $this->chassis = $chassis;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpperworks()
    {
        return $this->upperworks;
    }

    /**
     * @param mixed $upperworks
     * @return Crane
     */
    public function setUpperworks($upperworks)
    {
        $this->upperworks = $upperworks;
        return $this;
    }


}