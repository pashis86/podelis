<?php

namespace NFQ\NFQBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    /**
     * @Route("/showInfo")
     */
    public function showInfoAction()
    {
        $crane = $this->get('app.crane');
        return $this->render('SandboxBundle:Show:show_info.html.twig', array(
            'crane' => $crane,
        ));
    }
}
