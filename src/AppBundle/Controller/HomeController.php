<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
use AppBundle\Form\TestQuestionType;
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
     * @Route("/question/{id}", name="question")
     */
    public function testAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Question');
        $question = $repository->findOneBy(['id' => $id]);

        if($question)
        {
            $form = $this->createForm(TestQuestionType::class, ['question' => $question]);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                dump($form->getData());
                die();
            }

            return $this->render('@App/Home/question.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->render('@App/Home/404.html.twig');
    }
}
