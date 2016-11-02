<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 16.11.2
 * Time: 18.49
 */

namespace NFQ\SandboxBundle\Event;

use NFQ\SandboxBundle\Service\Bike;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubscribeListener implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return array(
      Events::PRE_CREATE => "makeChanges"
    );
  }
  
  public function makeChanges($event)
  {
    /** @var Bike $bike */
    $bike = $event->getBike();
    $bike->setFrame('Subscriber working');
  }
}