<?php
/**
 * Created by PhpStorm.
 * User: sarunas
 * Date: 16.11.2
 * Time: 20.48
 */

namespace NFQ\NFQBundle\Event;

use NFQ\NFQBundle\Service\Crane;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
class SubscribeListener implements EventSubscriberInterface {
    public static function getSubscribedEvents() {
        return array(
            Events::PRE_CREATE => "makeChanges"
        );
    }

    /** @param PreCreateEvent $event */
    public function makeChanges($event)
    {
        /** @var Crane $crane */
        $crane= $event->getCrane();
        $crane->setChassis('Subscriber working');
    }
}