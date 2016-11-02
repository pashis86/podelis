<?php
/**
 * Created by PhpStorm.
 * User: sarunas
 * Date: 16.11.2
 * Time: 20.45
 */

namespace NFQ\NFQBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class PreCreateEvent extends Event
{
    const NAME = 'app.pre_create';
    private $crane;
    public function __construct($crane)
    {
        $this->setCrane($crane);
    }
    /**
     * @return mixed
     */
    public function getCrane()
    {
        return $this->crane;
    }
    /**
     * @param mixed $crane
     */
    public function setCrane($crane)
    {
        $this->crane = $crane;
    }
}