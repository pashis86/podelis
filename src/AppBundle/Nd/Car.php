<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.12
 * Time: 21.10
 */

namespace AppBundle\Nd;


class Car extends Engine
{

    private $revLimit;

    private $sound;

    public function start()
    {
        return $this->sound * $this->capacity;
    }

    public function accelerate()
    {
        return $this->revLimit;
    }

    public function fillUp()
    {
        $this->fuelType;
    }
}