<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * User
 *
 * @ORM\Table(name="users")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements AdvancedUserInterface /*, \Serializable <-- del sito buna segmentation error */
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
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="6", minMessage="Password must be at least 6 characters long")
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="30", minMessage="Vartotojo vardas per trumpas", maxMessage="Vartotojo vardas per ilgas")
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="30")
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    private $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @var integer
     *
     * @ORM\Column(name="tests_taken", type="integer", nullable=true)
     */
    private $testsTaken;

    /**
     * @var integer
     *
     * @ORM\Column(name="correct", type="integer", nullable=true)
     */
    private $correct;

    /**
     * @var integer
     *
     * @ORM\Column(name="incorrect", type="integer", nullable=true)
     */
    private $incorrect;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_spent", type="datetime", nullable=true)
     */
    private $timeSpent;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     * @return User
     */
    public function setActive(bool $active): User
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password = null): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }


    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email = null): User
    {
        $this->email = $email;
        return $this;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return User
     */
    public function setSurname(string $surname = null): User
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param int $level
     * @return User
     */
    public function setLevel(int $level): User
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt(\DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt(\DateTime $updatedAt): User
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }


    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return User
     */
    public function setToken(string $token = null)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId(string $facebookId): User
    {
        $this->facebookId = $facebookId;
        return $this;
    }

    /**
     * @return int
     */
    public function getTestsTaken(): int
    {
        return $this->testsTaken;
    }

    /**
     * @param int $testsTaken
     * @return User
     */
    public function setTestsTaken(int $testsTaken): User
    {
        $this->testsTaken = $testsTaken;
        return $this;
    }

    /**
     * @return int
     */
    public function getCorrect(): int
    {
        return $this->correct;
    }

    /**
     * @param int $correct
     * @return User
     */
    public function setCorrect(int $correct): User
    {
        $this->correct = $correct;
        return $this;
    }

    /**
     * @return int
     */
    public function getIncorrect(): int
    {
        return $this->incorrect;
    }

    /**
     * @param int $incorrect
     * @return User
     */
    public function setIncorrect(int $incorrect): User
    {
        $this->incorrect = $incorrect;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTimeSpent(): \DateTime
    {
        return $this->timeSpent;
    }

    /**
     * @param \DateTime $timeSpent
     * @return User
     */
    public function setTimeSpent(\DateTime $timeSpent): User
    {
        $this->timeSpent = $timeSpent;
        return $this;
    }

    public function __construct()
    {
        $this->active = false;
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        $this->level = 1;
        $this->correct = 0;
        $this->incorrect = 0;
        $this->testsTaken = 0;
        $this->timeSpent = new \DateTime('1000-01-01 00:00:00');
    }
    
    /**
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->name,
            $this->surname,
            $this->password,
            $this->active
        ]);
    }


    public function unserialize($serialized)
    {
         list(
            $this->id,
            $this->username,
            $this->name,
            $this->surname,
            $this->password,
            $this->active
            ) = $this->unserialize($serialized);
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->active;
    }
}

