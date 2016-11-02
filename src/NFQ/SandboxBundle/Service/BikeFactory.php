<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 16.11.2
 * Time: 17.37
 */

namespace NFQ\SandboxBundle\Service;


use NFQ\SandboxBundle\Event\Events;
use NFQ\SandboxBundle\Event\PreCreateEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class BikeFactory
{
  /**
   * @var EventDispatcher
   */
  private $eventDispatcher;
  public function __construct($eventDispatcher)
  {
    $this->eventDispatcher = $eventDispatcher;
  }
  public function create()
  {
    $bike = new Bike();
    $bike->setFrame('Carbon');
    $this->eventDispatcher->dispatch(Events::PRE_CREATE, new PreCreateEvent($bike));
    return $bike;
  }
}