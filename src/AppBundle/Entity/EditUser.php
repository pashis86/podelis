<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.10.26
 * Time: 12.04
 */

namespace AppBundle\Entity;



use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;


class EditUser
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Įveskite savo vardą")
     * @Assert\Length(min="3", minMessage="Name must be at least 3 charcters long")
     */
    private $name;

    /**
     * @var string
     */
    private $surname;

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @var string
     * @UserPassword(message = "Neteisingas slaptažodis")
     */
    private $password;

    public function __construct(User $user)
    {
        $this->name = $user->getName();
        $this->surname = $user->getSurname();

    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return EditUser
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set password
     *
     * @param string $password
     *
     * @return EditUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}