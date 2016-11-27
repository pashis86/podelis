<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 16.11.17
 * Time: 10.28
 */

namespace AppBundle\Service;


use AppBundle\AppBundle;
use Doctrine\ORM\EntityManager;

class UserControl
{
    private $em;

    /**
     * UserControl constructor.
     * @param EntityManager $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    public function userCount()
    {
        return $this->em->getRepository('AppBundle:User')->createQueryBuilder('u')
            ->select('count(u)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}