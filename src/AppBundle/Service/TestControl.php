<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.3
 * Time: 16.43
 */

namespace AppBundle\Service;


use AppBundle\Entity\Answer;
use AppBundle\Entity\Question;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class TestControl
{
    private $questions;

    private $questionGroups;

    private $session;

    private $security;

    private $em;

    private $answers;

    /** @param Session $session
     * @param EntityManager $em
     * @param TokenStorage $security
     */
    public function __construct($session, $security, $em)
    {
        $this->questions = [];
        $this->session = $session;
        $this->security = $security;
        $this->answers = $session->get('answered');
        $this->em = $em;
        $this->questionGroups = $session->get('questionGroups');

        foreach ($this->questionGroups as $group) {
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
        foreach ($this->questions as $question) {

            if ($question == $questionId) {
                return true;
            }
        }
        return false;
    }

    public function addAnswer($id, $answer)
    {
        if ($this->session->get('endsAt') >= new \DateTime()) {
            $answered = $this->session->get('answered');
            $answered[$id] = $answer;
            $this->session->set('answered', $answered);
        }

    }

    public function submit($id, $answer)
    {
        if ($this->session->get('endsAt') >= new \Datetime()) {

            $answered = $this->session->get('answered');
            $answered[$id] = $answer;

            $this->session->set('answered', $answered);
            $this->checkAnswers();
        }

        else {
            $this->checkAnswers();
        }
    }

    public function setTimeLimit($timePerQuestion)
    {
        if (preg_match('#[0-9]+#', $timePerQuestion, $time)) {
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
            foreach ($group as $key => $question) {

                if ($question->getId() == $currentQ) {
                    return $key + 1;
                }
            }
        }
        return false;
    }

    public function array_equal($a, $b)
    {
        return (
            is_array($a) && is_array($b) &&
            count($a) == count($b) &&
            array_diff($a, $b) === array_diff($b, $a)
        );
    }

    public function checkAnswers()
    {
        if (count($this->session->get('isCorrect')) != count($this->questions)) {

            $ended = new \DateTime();
            if($this->session->get('endsAt') <= $ended){
                $ended = $this->session->get('endsAt');
            }
            $started = $this->session->get('started');

            $this->session->set('isCorrect', []);
            $this->session->set('timeSpent', date_diff($ended, $started));
            $this->session->set('endsAt', new \DateTime());

            foreach ($this->questions as $question) {
                $correctAns = $this->em->getRepository('AppBundle:Answer')
                    ->findBy(['question' => $question, 'correct' => true]);

                $pickedAnswers = (array_key_exists($question, $this->answers) ? $this->answers[$question] : null);

                if (!is_array($pickedAnswers)) {
                    $answer = $pickedAnswers;
                    $pickedAnswers = [$answer];
                }

                $isCorrect = $this->session->get('isCorrect');

                if ($this->array_equal($correctAns, $pickedAnswers) && !$this->isQuestionSolved($question)) {
                    $isCorrect[$question] = true;
                    $this->session->set('isCorrect', $isCorrect);
                } else {
                    $isCorrect[$question] = false;
                    $this->session->set('isCorrect', $isCorrect);
                }
            }
            /** @var User $user */
            $user = $this->security->getToken()->getUser();
            if ($user != 'anon.' && $this->session->get('trackResults')) {
                $user->updateStats($this->session->get('timeSpent'), $this->session->get('isCorrect'));
                $this->em->persist($user);
                $this->em->flush();
            }
        }
    }

    /** @param Question $question */
    public function prepareSelectedOptions($question, $answered, $id)
    {
        $checkedAnswers = (array_key_exists($id, $answered) ? $answered[$id] : null);

        if ($checkedAnswers == null)
            return $checkedAnswers;

        if ($checkedAnswers instanceof ArrayCollection) {

            foreach ($checkedAnswers as $key => $answer) {

                if ($answer instanceof Answer) {
                    $checkedAnswers[$key] = $this->em->merge($answer);
                }
                else{
                    continue;
                }
            }
            return $checkedAnswers;
        }
        else{

            return $this->em->merge($checkedAnswers);
        }
    }

    public function isQuestionSolved($id)
    {
        $solved = $this->session->get('solved');
        foreach ($solved as $key => $value)
        {
            if($key == $id && $value == true)
                return true;
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
     * @return TestControl
     */
    public function setQuestions(array $questions)
    {
        $this->questions = $questions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuestionGroups()
    {
        return $this->questionGroups;
    }

    /**
     * @param mixed $questionGroups
     * @return TestControl
     */
    public function setQuestionGroups($questionGroups)
    {
        $this->questionGroups = $questionGroups;
        return $this;
    }

}