<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.12.3
 * Time: 11.48
 */

namespace AppBundle\Validator;


use AppBundle\Entity\Answer;
use AppBundle\Entity\Question;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AnswersCollection
{
    /**
     * @param Question $question
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public static function hasCorrectAnswer($question, ExecutionContextInterface $context, $payload)
    {
        $correctAnswers = 0;
        /** @var Answer $answer */
        foreach ($question->getAnswers() as $answer) {
            if ($answer->getCorrect()) {
                $correctAnswers++;
                break;
            }
        }

       $correctAnswers == 0 ? $context->buildViolation('At least one answer has to be correct!')
            ->atPath('title')
            ->addViolation() : null;
    }
}