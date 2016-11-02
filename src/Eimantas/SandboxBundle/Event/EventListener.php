<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.2
 * Time: 13.26
 */

namespace Eimantas\SandboxBundle\Event;


use Eimantas\SandboxBundle\Service\Discovery;

class EventListener
{
    /**
     * @param PreCreateEvent $event
     */
    public function makeChanges($event)
    {
        /** @var Discovery $discovery */
        $discovery = $event->getDiscovery();
        $discovery->setEngine('RS-25 SLS')
            ->setFuelLoad(120000)
            ->setHull('100%')
            ->setCrew(8);
    }

}