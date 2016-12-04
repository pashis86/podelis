<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.6
 * Time: 17.27
 */

namespace AppBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Session\Session;

class TestStarter
{
    private $session;

    /**@param Session $session */
    public function __construct($session)
    {
        $this->session = $session;
    }

    /**
     * @param bool $trackResults
     * @param array $questions
     * @param string $timePerQuestion
     */
    public function startTest($questions, $timePerQuestion, $trackResults)
    {
        $test = [
            'questionGroups' => $questions,
            'trackResults' => $trackResults,
            'solved' => [],
            'started' => new \DateTime(),
            'endsAt' => new \DateTime($this->setTimeLimit($timePerQuestion, $questions)),
            'answered' => []
        ];

        $this->session->clear();
        $this->session->replace($test);
    }

    public function setTimeLimit($timePerQuestion, $questions)
    {
        if (preg_match('#[0-9]+#', $timePerQuestion, $time)) {
            $time = intval($time);
            $time = $time * count($questions, COUNT_RECURSIVE) - count($questions);

            return preg_replace('#[0-9]+#', $time, $timePerQuestion);
        }
        throw new \Exception('Invalid argument %s', $timePerQuestion);
    }
}