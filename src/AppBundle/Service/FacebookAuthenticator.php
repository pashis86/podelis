<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.10.28
 * Time: 23.14
 */

namespace AppBundle\Service;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookAuthenticator extends SocialAuthenticator
{

    private $clientRegistry;
    private $em;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManager $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // TODO: Implement start() method.
    }

    public function getCredentials(Request $request)
    {
        if($request->getPathInfo() != '/connect/facebook/check')
        {
            return;
        }
        return $this->fetchAccessToken($this->getFacebookClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->getFacebookClient()
            ->fetchUserFromToken($credentials);

        $email = $facebookUser->getEmail();
        $repository = $this->em->getRepository('AppBundle:User');
        $existingUser = $repository->findOneBy(['facebookId' => $facebookUser->getId()]);

        if($existingUser)
        {
            return $existingUser;
        }

        $user = $this->em->getRepository('AppBundle:User')
            ->findOneBy(['email' => $email]);

        if(!$user)
        {
            $user = new User();
            $username = substr($facebookUser->getFirstName(), 0, 3) .
                strtolower(substr($facebookUser->getLastName(), 0, 3));

            $all = $repository->findBy(['username' => $username]);
            if($all){
               $username = $username . count($all);
            }
            $user->setFacebookId($facebookUser->getId())
                ->setUsername($username)
                ->setEmail($facebookUser->getEmail())
                ->setName($facebookUser->getFirstName())
                ->setSurname($facebookUser->getLastName())
                ->setActive(true);

            $this->em->persist($user);
            $this->em->flush();
        }
        return $user;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    private function getFacebookClient()
    {
        return $this->clientRegistry->getClient('facebook');
    }
}