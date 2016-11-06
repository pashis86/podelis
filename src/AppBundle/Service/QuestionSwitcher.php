<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.3
 * Time: 16.43
 */

namespace AppBundle\Service;


use AppBundle\Entity\Question;
use Symfony\Component\HttpFoundation\Session\Session;

class QuestionSwitcher
{
    private $questions;

    /** @param Session $session */
    public function __construct($session)
    {
        $this->questions = [];
        $questionGroups = $session->get('questionGroups');

        foreach ($questionGroups as $group){
            /** @var Question $question */
            foreach ($group as $question)
                array_push($this->questions, $question->getId());
        }
    }

    public function getNext($currentQ)
    {
        $nextQ = array_search($currentQ, $this->questions) + 1;
        return (array_key_exists($nextQ, $this->questions) ? $this->questions[$nextQ] : $this->questions[0]);
    }

    public function getPrevious($currentQ)
    {
        $previousQ = array_search($currentQ, $this->questions) - 1;
        return (array_key_exists($previousQ, $this->questions) ? $this->questions[$previousQ] : end($this->questions));
    }

    public function getCurrent($currentQ)
    {
        return $this->questions[$currentQ - 1];
    }

    /**
     * @return array
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }

    /**
     * @param array $questions
     * @return QuestionSwitcher
     */
    public function setQuestions(array $questions): QuestionSwitcher
    {
        $this->questions = $questions;
        return $this;
    }

}