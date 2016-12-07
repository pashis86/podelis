<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.12.4
 * Time: 13.09
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Notification;
use AppBundle\Entity\Question;
use AppBundle\Entity\QuestionReport;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

class NotificationListener
{

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        $notification = new Notification();

        if ($entity instanceof QuestionReport) {
            if ($entity->getStatus() == 'Approved') {
                /** @var User $user */
                $user = $entity->getCreatedBy();
                $question = $entity->getQuestion();

                $question->addContributors($user);
                $user->addQuestionsContributed($question);

                $notification->userNotification($user, $entity);
                $this->notifyAdmins($em, $entity, true);

                $em->persist($notification);
                $em->remove($entity);
                $em->flush();
            } else if ($entity->getStatus() != 'Submitted') {
                $notification->userNotification($entity->getCreatedBy(), $entity);
                $this->notifyAdmins($em, $entity, true);

                $em->persist($notification);
                $em->remove($entity);
                $em->flush();
            }
        }
        if ($entity instanceof Question) {
            /** @var User $user */
            $user = $entity->getCreatedBy();
            if ($entity->getStatus() != 'Submitted') {
                $notification->userNotification($user, $entity);
                $em->persist($notification);
            }

            $this->notifyAdmins($em, $entity, true);
            $em->flush();
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if ($entity instanceof QuestionReport) {
            $this->notifyAdmins($em, $entity, false);
            $em->flush();
        }
        if ($entity instanceof Question) {
            $this->notifyAdmins($em, $entity, false);
            $em->flush();
        }
    }

    /**
     * @param string $role
     * @param EntityManager $em
     * @return array
     */
    public function getRoleGroup($role, $em)
    {
        return $em->getRepository('AppBundle:User')->findByRole($role);
    }

    /**
     * @param EntityManager $em
     * @param $entity
     * @param boolean $isUpdate
     */
    public function notifyAdmins($em, $entity, $isUpdate)
    {
        foreach ($this->getRoleGroup('ROLE_ADMIN', $em) as $admin) {
            $adminNot = new Notification();
            $adminNot->adminNotification($admin, $entity, $isUpdate);

            $em->persist($adminNot);
        }
    }
}
