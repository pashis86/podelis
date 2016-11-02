<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.2
 * Time: 13.09
 */

namespace Eimantas\SandboxBundle\Service;


use Eimantas\SandboxBundle\Event\Events;
use Eimantas\SandboxBundle\Event\PreCreateEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class DiscoveryFactory
{
    /** @var  EventDispatcher */
    private $eventDispatcher;

    public function __construct($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function create()
    {
        $discovery = new Discovery();
        $discovery
            ->setEngine('Aerojet Rocketdyne RS-25')
            ->setHull('80%')
            ->setFuelLoad(100000)
            ->setCrew(7);
        $this->eventDispatcher->dispatch(Events::PRE_CREATE, new PreCreateEvent($discovery));

        return $discovery;
    }
}