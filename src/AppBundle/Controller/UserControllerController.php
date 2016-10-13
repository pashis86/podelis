<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserControllerController extends Controller
{
    /**
     * @Route("/list1")
     */
    public function listAction()
    {
        return $this->render('AppBundle:UserController:list.html.twig', []);
    }

}
