<?php
namespace NFQ\SandboxBundle\Event;

use NFQ\SandboxBundle\Service\Bike;

class EventListener
{
  /**
   * @param PreCreateEvent $event
   */
  public function makeChanges($event)
  {
    /** @var Bike $bike */
    $car = $event->getBike();
    $car->setFrame('Alluminium');
  }
}