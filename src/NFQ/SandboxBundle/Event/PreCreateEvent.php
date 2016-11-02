<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 16.11.2
 * Time: 17.59
 */

namespace NFQ\SandboxBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class PreCreateEvent extends Event
{
  const NAME = 'app.pre_create';
  private $bike;
  public function __construct($bike)
  {
    $this->setBike($bike);
  }
  /**
   * @return mixed
   */
  public function getBike()
  {
    return $this->bike;
  }
  /**
   * @param mixed $bike
   */
  public function setBike($bike)
  {
    $this->bike = $bike;
  }
}