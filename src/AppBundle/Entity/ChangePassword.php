<?php
namespace AppBundle\Entity;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "Neteisingas slaptažodis"
     * )
     */
    protected $oldPassword;

    /**
     * @Assert\NotBlank(message = "Įveskite naująjį slaptažodį")
     * @Assert\Length(
     *      min = 6,
     *      max = 4096,
     *      minMessage = "Slaptažodį turi sudaryti ne mažiau nei 6 symboliai",
     *      maxMessage = "Slaptažodis per ilgas")
     */
    private $newPassword;


    public function getNewPassword()
    {
        return $this->newPassword;
    }

    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }
}