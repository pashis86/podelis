<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.3
 * Time: 16.43
 */

namespace AppBundle\Service;


use AppBundle\Entity\Question;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class QuestionSwitcher
{
    private $questions;

    private $questionGroups;

    private $session;

    private $security;

    private $em;

    /** @param Session $session
     * @param EntityManager $em
     * @param TokenStorage $security*/
    public function __construct($session, $security, $em)
    {
        $this->questions = [];
        $this->session = $session;
        $this->security = $security;
        $this->em = $em;
        $this->questionGroups = $session->get('questionGroups');

        foreach ($this->questionGroups as $group){
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

    public function questionInTest($questionId)
    {
        foreach ($this->questions as $question){
            if($question == $questionId){
                return true;
            }
        }
        return false;
    }

    public function addAnswer($id, $answer)
    {
        if($this->session->get('endsAt') >= new \DateTime()){
            $answered = $this->session->get('answered');
            $answered[$id] = $answer;
            $this->session->set('answered', $answered);
        }
    }

    public function submit($id, $answer)
    {

        if($this->session->get('endsAt') >= new \Datetime()){
            $answered = $this->session->get('answered');
            $answered[$id] = $answer;
            $this->session->set('answered', $answered);
            $this->session->set('isCorrect', []);
            $this->session->set('endsAt', new \DateTime());
            /** @var User $user */
            $user = $this->security->getToken()->getUser();

        }
    }

    public function setTimeLimit($timePerQuestion)
    {
       if(preg_match('#[0-9]+#', $timePerQuestion, $time)){
           $time = intval($time);
           $time = $time * count($this->questions);
          return preg_replace('#[0-9]+#', $time, $timePerQuestion);
       }
       throw new \Exception('Invalid argument %s', $timePerQuestion);
    }

    public function getCurrentIndex($currentQ)
    {
        foreach ($this->questionGroups as $group) {
            /**@var Question $question */
            foreach ($group as $key => $question){
                if($question->getId() == $currentQ){
                    return $key + 1;
                }
            }
        }
        return false;
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