<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.6
 * Time: 17.27
 */

namespace AppBundle\Service;


use AppBundle\Entity\Answer;
use AppBundle\Entity\Question;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class QuestionChecker
{
    private $session;

    private $questions;

    private $answers;

    private $em;

    /** @param Session $session
     * @param EntityManager $em */
    public function __construct($session, $em)
    {
        $this->session = $session;
        $this->questions = [];
        $this->em = $em;
        $this->answers = $session->get('answered');
        $questionGroups = $session->get('questionGroups');

        foreach ($questionGroups as $group){
            /** @var Question $question */
            foreach ($group as $question)
                array_push($this->questions, $question);
        }
    }

    function array_equal($a, $b) {
        return (
            is_array($a) && is_array($b) &&
            count($a) == count($b) &&
            array_diff($a, $b) === array_diff($b, $a)
        );
    }

    public function checkAnswers()
    {
        /** @var Question $question */
        foreach ($this->questions as $question){
            $qId = $question->getId();

            $qAnswers= $this->em->getRepository('AppBundle:Answer')
                ->findBy(['question' => $qId]);
            //$qAnswers = $question->getAnswers(); grazina PersistentCollection ir nera jokiu atsakymu

            /** @var Answer $answer */
            foreach ($qAnswers as $key => $answer){
                if(!$answer->getCorrect()){
                    unset($qAnswers[$key]);
                }
            }

            $pickedAnswers = (array_key_exists($qId, $this->answers) ? $this->answers[$qId] : null);

            if(!is_array($pickedAnswers)){
                $answer = $pickedAnswers;
                $pickedAnswers = [$answer];
            }

            $isCorrect = $this->session->get('isCorrect');

            if($this->array_equal($qAnswers, $pickedAnswers)){
                $isCorrect[$qId] = true;
                $this->session->set('isCorrect', $isCorrect);
            } else {
                $isCorrect[$qId] = false;
                $this->session->set('isCorrect', $isCorrect);
            }
        }
    }

    /** @param Question $question */
    public function prepareSelectedOptions($question, $answered, $id)
    {
        $checkedAnswers = (array_key_exists($id, $answered) ? $answered[$id]: null);

        if(!is_array($checkedAnswers)){
            $checkedAnswers = [$checkedAnswers];
        }

        if($checkedAnswers != null){
            foreach ($checkedAnswers as $key => $answer) {
                if($answer != null) {
                    $checkedAnswers[$key] = $this->em->merge($answer);
                }
            }
        }
        if(!$question->getCheckboxAnswers()){
            $checkedAnswers = $checkedAnswers[0];
        }

        return $checkedAnswers;
    }

}