<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.23
 * Time: 22.42
 */

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Model\User;

class RedirectUserListener
{
    private $tokenStorage;
    private $router;

    public function __construct(TokenStorageInterface $t, RouterInterface $r)
    {
        $this->tokenStorage = $t;
        $this->router = $r;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->isUserLogged() && $event->isMasterRequest()) {
            $currentRoute = $event->getRequest()->attributes->get('_route');
            if ($this->isAuthenticatedUserOnAnonymousPage($currentRoute)) {

                $url = $this->router->generate('homepage');
                $response = new RedirectResponse($url);
                $event->setResponse($response);
            }
        }
    }

    private function isUserLogged()
    {
        $token = $this->tokenStorage->getToken();

        if($token == null)
            return false;

        return $token->getUser() instanceof User;
    }

    private function isAuthenticatedUserOnAnonymousPage($currentRoute)
    {
        return in_array(
            $currentRoute,
            ['fos_user_security_login', 'fos_user_resetting_request', 'app_user_registration']
        );
    }
}