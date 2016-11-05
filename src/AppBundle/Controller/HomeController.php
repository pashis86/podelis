<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $securityContext = $this->get('security.authorization_checker');

        if(!$securityContext->isGranted('IS_AUTHENTICATED_FULLY') ||
            !$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $authenticationUtils = $this->get('security.authentication_utils');

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();

            // last username entered by the user
            $lastEmail = $authenticationUtils->getLastUsername();
            //  die($lastEmail);
            return $this->render('AppBundle:Home:index.html.twig', [
                'last_email' => $lastEmail,
                'error'         => $error,
            ]);
        }
    }

    /**
     * @Route("/list", name="posts_list")
     */
    public function listAction()
    {
        $exampleService = $this->get('app.example');

        $posts = $exampleService->getDummyPosts();

        return $this->render('AppBundle:Home:list.html.twig', [
            'posts' => $posts,
        ]);
    }
}
