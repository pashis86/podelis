<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Symfony\Bridge\Twig\TwigEngine;



class RegisterEmail
{
    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, TwigEngine $engine)
    {
        $this->mailer = $mailer;
        $this->templating = $engine;
    }

    public function sendEmail(User $user)
    {

        $message = \Swift_Message::newInstance()
            ->setSubject('Sveikiname užsiregistravus!')
            ->setFrom('podelis@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('@App/Emails/registerEmail.html.twig',
                    array('name' => $user->getName())), 'text/html');
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