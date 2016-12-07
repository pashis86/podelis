<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $usercount  = $this->get('app.user')->userCount();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Book')->findAll();

        return $this->render('AppBundle:Home:index.html.twig', [
            'usercount'     => $usercount,
            'categories'    => $categories
        ]);
    }

    /**
     * @Route("/leaderboard", name="leaderboard")
     */
    public function leaderboardAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:User');
            $best       = $repository->getLeaderBoard($request->request);

            return new JsonResponse(json_encode($best), 200, array(), true);
        }
        return $this->render('@App/Home/leaderboard.html.twig', []);
    }

    /**
     * @Route("/failures", name="failures")
     */
    public function failuresAction()
    {
        return $this->render('AppBundle:Home:failures.html.twig', [
        ]);
    }
}
