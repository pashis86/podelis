<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.2
 * Time: 20.29
 */

namespace Eimantas\SandboxBundle\Event;



use Eimantas\SandboxBundle\Service\Discovery;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [Events::PRE_CREATE => 'makeChanges'];
    }

    /** @param PreCreateEvent $event */
    public function makeChanges($event)
    {
        /** @var Discovery $discvery */
        $discvery = $event->getDiscovery();
        $discvery->setCrew(10);
    }
}