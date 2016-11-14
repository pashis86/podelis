<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
use AppBundle\Form\TestQuestionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('AppBundle:Home:index.html.twig', []);
    }

    /**
     * @Route("/test-options", name="test_options")
     * @Security("has_role('ROLE_USER')")
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
            $session->clear();
            $session->set('questionGroups', $questionGroups);
            $session->set('trackResults', true);
            $testControl = $this->get('app.test_control');

            $session->set('endsAt', new \DateTime($testControl->setTimeLimit('+1 minute')));
            $session->set('started', new \DateTime());
            $session->set('answered', []);
            return $this->redirectToRoute('question', ['id' => $questionGroups[0][0]->getId()]);
        }

        return $this->render('@App/TestPages/test-options.html.twig', [
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
        $session->clear();

        $session->set('questionGroups', $questions);

        $testControl = $this->get('app.test_control');
        $session->set('trackResults', false);
        $session->set('started', new \DateTime());
        $session->set('endsAt', new \DateTime($testControl->setTimeLimit('+1 minute')));
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
        $testControl = $this->get('app.test_control');
        $question = $repository->findOneBy(['id' => $id]);

        $session = $this->get('session');

        if($question && $testControl->questionInTest($id))
        {
            if($session->get('endsAt') <= new \DateTime('now')){
                return $this->redirectToRoute('testResults', ['id' => $testControl->getQuestionGroups()[0][0]->getId()]);
            }
            $form = $this->createForm(TestQuestionType::class,
                ['question' => $question, 'answered' => $session->get('answered')]);
            $form->handleRequest($request);

            if($form->get('next')->isClicked()){
                $testControl->addAnswer($id, $form['answers']->getData());
                return $this->redirectToRoute('question', ['id' => $testControl->getNext($id)]);
            }

            if($form->get('previous')->isClicked()){
                $testControl->addAnswer($id, $form['answers']->getData());
                return $this->redirectToRoute('question', ['id' => $testControl->getPrevious($id)]);
            }

            if($form->get('submit')->isClicked()){
                $testControl->submit($id, $form['answers']->getData());

                return $this->redirectToRoute('testResults', ['id' => $testControl->getQuestionGroups()[0][0]->getId()]);
            }

            return $this->render('@App/TestPages/question.html.twig', [
                'form' => $form->createView(),
                'current' => $question,
                'index' => $testControl->getCurrentIndex($id)
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

        if($request->isXmlHttpRequest() && $session->get('endsAt') >= new \DateTime()){
            $answered = $session->get('answered');
            $repository = $this->getDoctrine()->getRepository('AppBundle:Answer');

            $question = $request->request->get('question');
            $answerIds = $request->request->get('answer');

            $answers = $repository->getAllChecked($answerIds);
            $answered[$question] = $answers;
            $session->set('answered', $answered);
        }
        return $this->render('@App/Home/404.html.twig');
    }

    /**
     * @Route("/solve-it", name="solveIt")
     */
    public function solveItAction(Request $request)
    {
        $session = $this->get('session');

        if($request->isXmlHttpRequest() && $session->get('endsAt') >= new \DateTime()){

            $repository = $this->getDoctrine()->getRepository('AppBundle:Answer');

            $question = $request->request->get('question');
            $correct = $repository->getCorrectIds($question);
            dump($correct);
            return new JsonResponse(json_encode($correct));
        }
        return $this->render('@App/Home/404.html.twig');
    }

    /**
     * @Route("/results/{id}", name="testResults")
     */
    public function testResultsAction(Request $request, $id)
    {
        $session = $this->get('session');
        $testControl = $this->get('app.test_control');
        $testControl->checkAnswers();

        $repository = $this->getDoctrine()->getRepository('AppBundle:Question');
        $question = $repository->findOneBy(['id' => $id]);

        if($question && $testControl->questionInTest($id))
        {
            $form = $this->createForm(TestQuestionType::class,
                ['question' => $question, 'answered' => $session->get('answered')]);
            $form->handleRequest($request);

            if($form->get('next')->isClicked()){
                return $this->redirectToRoute('testResults', ['id' => $testControl->getNext($id)]);
            }

            if($form->get('previous')->isClicked()){
                return $this->redirectToRoute('testResults', ['id' => $testControl->getPrevious($id)]);
            }

            if($form->get('submit')->isClicked()){
                $session->clear();
                return $this->redirectToRoute('homepage');
            }
            return $this->render('@App/TestPages/results.html.twig', [
                'form' => $form->createView(),
                'current' => $question,
                'index' => $testControl->getCurrentIndex($id)
            ]);
        }
        return $this->render('@App/Home/404.html.twig');
    }

    /**
     * @Route("/leaderboard/{page}", name="leaderboard")
     */
    public function leaderboardAction(Request $request, $page = 1)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        $order = $request->query->get('order');
        $order ? $order : $order = 'correct';

        $best = $repository->findBest($order, $page, $limit = 2);
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
