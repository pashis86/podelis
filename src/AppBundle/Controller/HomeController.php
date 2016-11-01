<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Home:index.html.twig', []);
    }

    /**
     * @Route("/test-options", name="test_options")
     */
    public function listAction(Request $request)
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $qRepository = $this->getDoctrine()->getRepository('AppBundle:Question');
            $questionGroup = $qRepository->getSpecificQuestions([
                'books' => $form['book']->getData(),
                'amount' => $form['amount']->getData()
            ]);

             return $this->render('@App/Home/test.html.twig', [
                 'questionGroup' => $questionGroup
             ]);

        }

        return $this->render('@App/Home/test-options.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction(Request $request, $questions)
    {

    }
}
