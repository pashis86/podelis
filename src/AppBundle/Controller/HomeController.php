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
            $switcher = $this->get('app.question_switcher');

            $session->set('endsAt', new \DateTime($switcher->setTimeLimit('+1 minute')));
            $session->set('answered', []);
            return $this->redirectToRoute('question', ['id' => $questionGroups[0][0]->getId()]);
        }

        return $this->render('@App/Home/test-options.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/quick-test", name="quick_test")
     */
    public function quickTestAction(Request $request)
    {
        $qRepository = $this->getDoctrine()->getRepository('AppBundle:Question');
        $questions = $qRepository->getRandomQuestions(10);

        $session = new Session();
        $session->set('questionGroups', $questions);

        $switcher = $this->get('app.question_switcher');
        $session->set('endsAt', new \DateTime($switcher->setTimeLimit('+1 minute')));
        $session->set('answered', []);

        return $this->redirectToRoute('question', ['id' => $questions[0][0]->getId()]);
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
        $questionGroups = $session->get('questionGroups');

        if($question && $switcher->questionInTest($id))
        {
            if($session->get('endsAt') <= new \DateTime('now')){
                return $this->redirectToRoute('testResults', ['id' => $questionGroups[0][0]->getId()]);
            }
            $form = $this->createForm(TestQuestionType::class,
                ['question' => $question, 'answered' => $session->get('answered')]);
            $form->handleRequest($request);

            if($form->get('next')->isClicked()){
                $switcher->addAnswer($id, $form['answers']->getData());
                return $this->redirectToRoute('question', ['id' => $switcher->getNext($id)]);
            }

            if($form->get('previous')->isClicked()){
                $switcher->addAnswer($id, $form['answers']->getData());
                return $this->redirectToRoute('question', ['id' => $switcher->getPrevious($id)]);
            }

            if($form->get('submit')->isClicked()){
                $switcher->submit($id, $form['answers']->getData());
                $this->get('app.question_checker')->checkAnswers();

                return $this->redirectToRoute('testResults', ['id' => $questionGroups[0][0]->getId()]);
            }

            return $this->render('@App/Home/question.html.twig', [
                'form' => $form->createView(),
                'current' => $question,
                'index' => $switcher->getCurrentIndex($id)
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

        if($request->isXmlHttpRequest() && $session->get('endsAt') >= new \DateTime()){
            $question = $request->request->get('question');
            $answerIds = $request->request->get('answer');

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

        $switcher = $this->get('app.question_switcher');
        $repository = $this->getDoctrine()->getRepository('AppBundle:Question');
        $question = $repository->findOneBy(['id' => $id]);

        if($question && $switcher->questionInTest($id))
        {
            $form = $this->createForm(TestQuestionType::class,
                ['question' => $question, 'answered' => $session->get('answered')]);
            $form->handleRequest($request);

            if($form->get('next')->isClicked()){
                return $this->redirectToRoute('testResults', ['id' => $switcher->getNext($id)]);
            }

            if($form->get('previous')->isClicked()){
                return $this->redirectToRoute('testResults', ['id' => $switcher->getPrevious($id)]);
            }

            if($form->get('submit')->isClicked()){

            }
            return $this->render('@App/Home/results.html.twig', [
                'form' => $form->createView(),
                'current' => $question,
                'index' => $switcher->getCurrentIndex($id)
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
