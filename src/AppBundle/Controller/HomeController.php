<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
use AppBundle\Form\TestQuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

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
            $questionGroups = $qRepository->getSpecificQuestions([
                'books' => $form['book']->getData(),
                'amount' => $form['amount']->getData()
            ]);

            $session = new Session();
            $session->set('questionGroups', $questionGroups);

            /* return $this->render('@App/Home/test.html.twig', [
                 'questionGroups' => $questionGroups
             ]);*/
           // dump($session->get('questionGroups'));
           // die();
            return $this->testAction($request, 1);

        }

        return $this->render('@App/Home/test-options.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/question/{id}", name="question")
     */
    public function testAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Question');
        $question = $repository->findOneBy(['id' => $id]);

        $session = $this->get('session');

        if($question)
        {
            $form = $this->createForm(TestQuestionType::class, ['question' => $question]);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                dump($form->getData());
                die();
            }

            return $this->render('@App/Home/question.html.twig', [
                'form' => $form->createView(),
                'session' => $session->get('questionGroups')
            ]);
        }
        return $this->render('@App/Home/404.html.twig');
    }
}
