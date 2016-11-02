<?php
/**
 * Created by PhpStorm.
 * User: eimantas
 * Date: 16.11.2
 * Time: 21.04
 */

namespace Eimantas\SandboxBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    /**
     * @Route("/discovery", name="show")
     */
    public function showInfoAction()
    {
        $discovery = $this->get('app.discovery');
        return $this->render('@Sandbox/show.html.twig', [
            'discovery' => $discovery
        ]);
    }
}