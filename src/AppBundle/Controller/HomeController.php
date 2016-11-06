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
    public function testOptionsAction(Request $request)
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
            $session->set('answered', []);

            return $this->redirectToRoute('question', ['id' => $questionGroups[0][0]->getId()]);
        }

        return $this->render('@App/Home/test-options.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/question/{id}", name="question")
     *
     */
    public function testAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Question');
        $switcher = $this->get('app.question_switcher');
        $question = $repository->findOneBy(['id' => $id]);

        $session = $this->get('session');
        $answered = $session->get('answered');
        $questionGroups = $session->get('questionGroups');

        if($question && $switcher->questionInTest($id))
        {
            $form = $this->createForm(TestQuestionType::class, ['question' => $question, 'answered' => $answered]);
            $form->handleRequest($request);

            if($form->get('next')->isClicked()){
                $answered[$id] = $form['answers']->getData();
                $session->set('answered', $answered);
                $newId = $switcher->getNext($id);

                return $this->redirectToRoute('question', ['id' => $newId]);
            }

            if($form->get('previous')->isClicked()){
                $answered[$id] = $form['answers']->getData();
                $session->set('answered', $answered);
                $newId = $switcher->getPrevious($id);

                return $this->redirectToRoute('question', ['id' => $newId]);
            }

            if($form->get('submit')->isClicked()){
                $answered[$id] = $form['answers']->getData();
                $session->set('answered', $answered);
                $session->set('isCorrect', []);
                $this->get('app.question_checker')->checkAnswers();

                return $this->redirectToRoute('testResults', ['id' => $questionGroups[0][0]->getId()]);
            }

            return $this->render('@App/Home/question.html.twig', [
                'form' => $form->createView(),
                'current' => $id
            ]);
        }
        return $this->render('@App/Home/404.html.twig');
    }

    /**
     * @Route("/add-answer", name="questionChosen")
     */
    public function questionChosenAction(Request $request)
    {
        $session = $this->get('session');
        $answered = $session->get('answered');
        $repository = $this->getDoctrine()->getRepository('AppBundle:Answer');

        if($request->isXmlHttpRequest()){

            $question = $request->request->get('question');
            $answerIds = $request->request->get('answer');

            //$answer = $repository->findOneBy(['id' => $answerId]);
            $answers = $repository->getAllChecked($answerIds);

            $answered[$question] = $answers;
            $session->set('answered', $answered);
        }
        return $this->render('@App/Home/404.html.twig');
    }

    /**
     * @Route("/results/{id}", name="testResults")
     */
    public function testResultsAction(Request $request, $id)
    {
        $session = $this->get('session');

        $answered = $session->get('answered');
        $switcher = $this->get('app.question_switcher');
        $repository = $this->getDoctrine()->getRepository('AppBundle:Question');
        $question = $repository->findOneBy(['id' => $id]);

        if($question && $switcher->questionInTest($id))
        {
            $form = $this->createForm(TestQuestionType::class, ['question' => $question, 'answered' => $answered]);
            $form->handleRequest($request);

            if($form->get('next')->isClicked()){
                $newId = $switcher->getNext($id);
                return $this->redirectToRoute('testResults', ['id' => $newId]);
            }

            if($form->get('previous')->isClicked()){
                $newId = $switcher->getPrevious($id);
                return $this->redirectToRoute('testResults', ['id' => $newId]);
            }

            if($form->get('submit')->isClicked()){
                // dump($session->get('answered'));
            }
            return $this->render('@App/Home/results.html.twig', [
                'form' => $form->createView(),
                'currentQ' => $question
            ]);
        }
        return $this->render('@App/Home/404.html.twig');
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
}
