<?php

/**
 * Created by PhpStorm.
 * User: pc
 * Date: 16.11.2
 * Time: 17.34
 */
namespace NFQ\SandboxBundle\Service;

class Bike {

  private $wheels;

  private $frame;

  private $body;

  /**
   * @return mixed
   */
  public function getWheels() {
    return $this->wheels;
  }

  /**
   * @param mixed $wheels
   * @return Bike
   */
  public function setWheels($wheels) {
    $this->wheels = $wheels;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getFrame() {
    return $this->frame;
  }

  /**
   * @param mixed $frame
   * @return Bike
   */
  public function setFrame($frame) {
    $this->frame = $frame;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getBody() {
    return $this->body;
  }

  /**
   * @param mixed $body
   * @return Bike
   */
  public function setBody($body) {
    $this->body = $body;
    return $this;
  }

  public function start()
  {
    return $this->frame;
  }
}