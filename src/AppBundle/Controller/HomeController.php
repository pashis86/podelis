<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $usercount = $this->get('app.user')->userCount();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Book')->findAll();

        return $this->render('AppBundle:Home:index.html.twig', [
            'usercount' => $usercount,
            'categories' => $categories
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
     * @Route("/leaderboard", name="leaderboard")
     */
    public function leaderboardAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:User');
            $best = $repository->getLeaderBoard($request->request);

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

    /**
     * @Route("/api/user/data", name="user-data")
     */
    public function userDataAction(Request $request)
    {
        $securityContext = $this->get('security.authorization_checker');
        /** @var User $user */
        $user = $this->getUser();

        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY') ||
            $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')
        ) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Test');

            //$cat = $repository->bestResultFromEachCategory($user);

            $res=["100","10","20","30","40","50","60","70","80"];
            $data = "var data = google.visualization.arrayToDataTable([
                            ['Categories', 'Results', { role: 'style' }, { role: 'annotation' } ],
                            ['', $res[0], 'color: gray', 'PHP Basics'],
                            ['', $res[1], 'color: #76A7FA', 'Functions and Arrays'],
                            ['', $res[2], '', 'OOP'],
                            ['', $res[3], 'stroke-color: #703593; fill-color: #C5A5CF', 'Security'],
                            ['', $res[4], 'stroke-color: #871B47; fill-color: #BC5679; ', 'Data format and Types'],
                            ['', $res[5], 'gold', 'String and Patterns'],
                            ['', $res[6], 'color: #76A7FA', 'Database and SQL'],
                            ['', $res[7], 'silver', 'Web features'],
                            ['', $res[8], 'stroke-color: #703593; fill-color: #C5A5CF', 'INPUT and OUTPUT'],
                        ]);";
            /*
            $data = "var data = google.visualization.arrayToDataTable([
                            ['Category', 'Results', { role: 'style' } ],
                            ['Strings', 10, 'color: gray'],
                            ['Security', 84, 'color: #76A7FA'],
                            ['Inpute/output', 0, 'opacity: 0.2'],
                            ['AI', 0, 'stroke-color: #703593; stroke-width: 4; fill-color: #C5A5CF'],
                            ['Basics', 75, 'stroke-color: #871B47; stroke-opacity: 0.6; stroke-width: 8; fill-color: #BC5679; fill-opacity: 0.2']
                        ]);";*/

            return new JsonResponse(json_encode(['data' => $data]));

        } else {
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/api/user/category/{id}", name="user-data")
     */
    public function userCategoryResultsAction($id)
    {
        $securityContext = $this->get('security.authorization_checker');
        /** @var User $user */
        $user = $this->getUser();

        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY') ||
            $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')
        ) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Test');

            $cat = $repository->categoryResults($user,$id);

            $data=["100","10","20","30","40","50","60","70","80"];

            return new JsonResponse(json_encode(['data' => $data, 'id' => $id, 'cat' => $cat]));

        } else {
            return $this->redirectToRoute('homepage');
        }
    }
}

