<?php

namespace NFQ\SandboxBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ShowController extends Controller
{
    /**
     * @Route("/showInfo")
     */
    public function showInfoAction()
    {
        $bike = $this->container->get('app.bike');
        return $this->render('SandboxBundle:Show:show_info.html.twig', array(
            'bike' => $bike,
        ));
    }

}
