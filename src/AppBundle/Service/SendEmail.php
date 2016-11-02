<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Symfony\Bridge\Twig\TwigEngine;



class SendEmail
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
            ->setTo($user->getUsername())
            ->setBody(
                $this->templating->render('@App/Emails/registerEmail.html.twig',
                    ['name' => $user->getName(), 'text/html']));

        $this->mailer->send($message);
    }
}