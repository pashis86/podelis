<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $usercount = $this->get('app.user')->userCount();
        return $this->render('AppBundle:Home:index.html.twig', [
            'usercount' => $usercount
        ]);
    }

    /**
     * @Route("/profile", name="user")

     */
    public function userAction(Request $request)
    {
        //* @Security("has_role('ROLE_USER')")
        return $this->render('AppBundle:Home:user.html.twig', []);
    }


    /**
     * @Route("/leaderboard/{page}", name="leaderboard")
     */
    public function leaderboardAction(Request $request, $page = 1)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        $orderParams = $request->query->all();

        $best = $repository->findBest($orderParams, $page, $limit = 2);
        $maxPages = ceil($best->count() / $limit);

        if($page > $maxPages){
            return $this->render('@App/Home/404.html.twig');
        }

        return $this->render('@App/Home/leaderboard.html.twig', [
            'thisPage' => $page,
            'maxPages' => $maxPages,
            'best' => $best,
            'limit' => $limit
        ]);
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
