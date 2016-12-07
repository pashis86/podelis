<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Faker\Provider\cs_CZ\DateTime;

/**
 * Notification
 *
 * @ORM\Table(name="notifications")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationRepository")
 */
class Notification
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255)
     */
    private $content;

    /**
     * @var bool
     *
     * @ORM\Column(name="seen", type="boolean")
     */
    private $seen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="received", type="datetime")
     */
    private $received;



    public function __toString()
    {
        return $this->title;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Notification
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Notification
     */
    public function setTitle(string $title): Notification
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Notification
     */
    public function setContent(string $content): Notification
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Set seen
     *
     * @param boolean $seen
     *
     * @return Notification
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen
     *
     * @return bool
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * @return \DateTime
     */
    public function getReceived(): \DateTime
    {
        return $this->received;
    }

    /**
     * @param \DateTime $received
     * @return Notification
     */
    public function setReceived(\DateTime $received): Notification
    {
        $this->received = $received;
        return $this;
    }


    /**
     * @param User $user
     * @param $entity
     */
    public function userNotification($user, $entity)
    {
        $this->user = $user;
        $this->seen = false;

        if ($entity instanceof QuestionReport) {
            $this->content = 'Your report on question '.$entity->getQuestion()->getTitle().' status has been changed to '.$entity->getStatus().'.';
        } elseif ($entity instanceof Question) {
            $this->content = 'Your suggested question "'.$entity->getTitle().'"" status has been changed to '.$entity->getStatus().'.';
        } else {
            $this->content = 'New message!';
        }
        $this->title = strlen($this->content) > 50 ? substr($this->content, 0, 50).'...' : $this->content;
        $this->received = new \DateTime();
    }

    /**
     * @param User $admin
     * @param $entity
     * @param boolean $isUpdated
     */
    public function adminNotification($admin, $entity, $isUpdated)
    {
        $this->user = $admin;
        $this->seen = false;

        switch ($isUpdated) {
            case true:
                if ($entity instanceof QuestionReport) {
                    $this->content = 'Report No.'.$entity->getId().' on question '.$entity->getQuestion()->getTitle().' status has been changed to '.$entity->getStatus().' by '.$admin->getFullName().'.';
                } elseif ($entity instanceof Question) {
                    $this->content = 'Question "'.$entity->getTitle().'"" status has been changed to '.$entity->getStatus().'. by '.$admin->getFullName().'.';
                }
                break;

            case false:
                if ($entity instanceof QuestionReport) {
                    $this->content = 'New report on question '.$entity->getQuestion()->getTitle().' has been submitted by '.$entity->getCreatedBy()->getFullName().'.';
                } elseif ($entity instanceof Question) {
                    $this->content = 'New question for category '.$entity->getBook()->getTitle().' has been submitted by '.$entity->getCreatedBy()->getFullName().'.';
                }
                break;

            default:
                $this->content = 'New message!';
        }
        $this->title = strlen($this->content) > 150 ? substr($this->content, 0, 150).'...' : $this->content;
        $this->received = new \DateTime();
    }

    public function slugify()
    {
        return preg_replace(
            '/[^a-z0-9]/',
            '-',
            strtolower(trim(strip_tags($this->title)))
        );
    }
}
