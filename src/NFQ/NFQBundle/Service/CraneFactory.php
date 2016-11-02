<?php
/**
 * Created by PhpStorm.
 * User: sarunas
 * Date: 16.11.2
 * Time: 20.34
 */

namespace NFQ\NFQBundle\Service;


use NFQ\NFQBundle\Event\Events;
use NFQ\NFQBundle\Event\PreCreateEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class CraneFactory
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
        $crane = new Crane();
        $crane->setBody('Carbon');
        $crane->setChassis('29er');
        $crane->setUpperworks('18');
        $this->eventDispatcher->dispatch(Events::PRE_CREATE, new PreCreateEvent($crane));
        return $crane;
    }
}