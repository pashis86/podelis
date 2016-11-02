<?php
/**
 * Created by PhpStorm.
 * User: sarunas
 * Date: 16.11.2
 * Time: 20.41
 */

namespace NFQ\NFQBundle\Event;


use NFQ\NFQBundle\Service\Crane;

class EventListener
{
    /**
     * @param PreCreateEvent $event
     */
    public function makeChanges($event)
    {
        /** @var Crane $crane */
        $crane = $event->getCrane();
        $crane->setBody('EventListener working');
    }
}