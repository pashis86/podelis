<?php

namespace AppBundle\Repository;

/**
 * QuestionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuestionRepository extends \Doctrine\ORM\EntityRepository
{
    public function getSpecificQuestions($options)
    {
        $totalRows = $this->createQueryBuilder('q')
            ->select('q.id')
            ->where('q.book IN (:books)')
            ->setParameter('books', $options['books'])
            ->getQuery()
            ->getResult();

        shuffle($totalRows);
        array_splice($totalRows, $options['amount']);

        return $questions = $this->createQueryBuilder('q')
            ->select('q')
            ->where('q.id IN (:ids)')
            ->setParameter('ids', $totalRows)
            ->setMaxResults($options['amount'])
            ->getQuery()
            ->getResult();
    }
}
