<?php

namespace AppBundle\Nd;

/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.12
 * Time: 21.09
 */
abstract class Engine
{

    protected $capacity;

    protected $power;

    protected $fuelType;

    abstract public function start();

    abstract public function accelerate();
}