<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.3
 * Time: 16.43
 */

namespace AppBundle\Service;


use AppBundle\Entity\Question;

class QuestionSwitcher
{
    public function getNext($questionGroups, $questionId)
    {
        $allIds = [];
        foreach ($questionGroups as $group){
            /** @var Question $question */
            foreach ($group as $question)
            array_push($allIds, $question->getId());
        }
        $nextQ = array_search($questionId, $allIds) + 1;

        if(array_key_exists($nextQ, $allIds)){
            return $allIds[$nextQ];
        }
        else{
            return $allIds[0];
        }
    }

    public function getPrevious($questionGroups, $questionId)
    {
        $allIds = [];
        foreach ($questionGroups as $group){
            /** @var Question $question */
            foreach ($group as $question)
                array_push($allIds, $question->getId());
        }
        $previousQ = array_search($questionId, $allIds) - 1;

        if(array_key_exists($previousQ, $allIds)){
            return $allIds[$previousQ];
        }
        else{
            return end($allIds);
        }
    }
}