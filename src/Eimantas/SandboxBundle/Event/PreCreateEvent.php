<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.2
 * Time: 13.22
 */

namespace Eimantas\SandboxBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class PreCreateEvent extends Event
{
    const NAME = 'app.pre_create';

    private $discovery;

    public function __construct($discovery)
    {
        $this->discovery = $discovery;
    }

    /**
     * @return mixed
     */
    public function getDiscovery()
    {
        return $this->discovery;
    }

    /**
     * @param mixed $discovery
     */
    public function setDiscovery($discovery)
    {
        $this->discovery = $discovery;
    }

}