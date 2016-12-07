<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Symfony\Bridge\Twig\TwigEngine;

class EmailSender
{
    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, TwigEngine $engine)
    {
        $this->mailer = $mailer;
        $this->templating = $engine;
    }

    public function registrationEmail(User $user)
    {

        $message = \Swift_Message::newInstance()
            ->setSubject('Sveikiname užsiregistravus!')
            ->setFrom('podelis@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('@App/Emails/registerEmail.html.twig', [
                        'name' => $user->getName(),
                        'token' => $user->getConfirmationToken(),
                        'text/html'
                    ]
                )
            );
        /*
         * If you also want to include a plaintext version of the message
        ->addPart(
            $this->renderView(
                'Emails/registration.txt.twig',
                array('name' => $name)
            ),
            'text/plain'
        )
        */
        $this->mailer->send($message);
    }

    public function passResetEmail(User $user, $plainPass)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Password has been reset')
            ->setFrom('podelis@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('@App/Emails/registerEmail.html.twig', [
                        'name' => $user->getName(),
                        'plainPass' => $plainPass,
                        'text/html'
                    ]
                )
            );
        /*
         * If you also want to include a plaintext version of the message
        ->addPart(
            $this->renderView(
                'Emails/registration.txt.twig',
                array('name' => $name)
            ),
            'text/plain'
        )
        */
        $this->mailer->send($message);
    }
}
