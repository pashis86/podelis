<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.12.4
 * Time: 13.09
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\QuestionReport;
use AppBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AddContribution
{

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof QuestionReport) {
            if ($entity->getStatus() == 'Approved') {
                /** @var User $user */
                $user = $entity->getCreatedBy();
                $question = $entity->getQuestion();
                $question->addContributors($user);
                $user->addQuestionsContributed($entity->getQuestion());

                $args->getEntityManager()->remove($entity);
                $args->getEntityManager()->flush();
            }
        }
    }
}