<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\EditUserType;
use AppBundle\Form\ResetPasswordType;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SecurityController extends Controller
{
    /**
     * @Security("!has_role('ROLE_USER')")
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
            $user = new User();
            $form = $this->createForm(UserType::class, $user);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPassword());
                $token = bin2hex(random_bytes(81));
                $user->setPassword($password)->setConfirmationToken($token);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->get('app.send_email')->registrationEmail($user);

                return new Response($this->renderView('@App/SuccessPages/regComplete.html.twig'));
            }

            return $this->render(
                '@App/Security/register.html.twig',
                ['form' => $form->createView()]
            );
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/profile/edit", name="keistiDuomenis")
     */
    public function userEditAction(Request $request) {
        /** @var User $user */
        $user = $this->getUser();

            $form = $this->createForm(EditUserType::class, $user);
            $form->handleRequest($request);

            if($form->isSubmitted()) {

                if($form->isValid()) {

                    $user->setUpdatedAt(new \DateTime('now'));
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('success', 'Duomenys sėkmingai pakeisti!');
                }
            }
            return $this->render('@App/Security/editUser.html.twig', array(
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/profile/password", name="keistiSlaptazodi")
     * @Security("has_role('ROLE_USER')")
     *
     */
    public function userPasswordAction(Request $request) {

        /** @var User $user */
        $user = $this->getUser();

        if(!$user->getFacebookId() && !$user->getGoogleId()) {

            $form = $this->createForm(ChangePasswordType::class);

            $form->handleRequest($request);

            if($form->isSubmitted()) {

                if($form->isValid()) {

                    $pass = $this->get('security.password_encoder')
                        ->encodePassword($user, $form['newPassword']->getData());

                    $user->setPassword($pass);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('success', 'Duomenys sėkmingai pakeisti!');
                }
            }
            return $this->render('@App/Security/changePass.html.twig', array(
                'form' => $form->createView()
            ));
        }
        else {
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/activate/{token}", name="userActivation")
     */
    public function userActivationAction($token)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        /** @var User $user */
        $user = $repository->findOneBy(['confirmationToken' => $token]);
        if($user){
            $repository->activateUser($user);

            return new Response($this->renderView('@App/SuccessPages/userActivated.html.twig'));
        }
        return $this->render('@App/Home/404.html.twig');
    }

    /**
     * @Route("/forgot-password", name="forgotPassword")
     */
    public function forgotPassAction(Request $request)
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $repository = $this->getDoctrine()->getRepository('AppBundle:User');

            /** @var User $user */
            $user = $repository->findOneBy(
                ['email' => $form['email']->getData(),
                    'username' => $form['username']->getData()]);

            if($user){
                $plainPass = substr(md5(microtime()),rand(0,26),8);
                $newPass = $this->get('security.password_encoder')->encodePassword($user, $plainPass);
                $repository->resetPassword($user, $newPass);
                $this->get('app.send_email')->passResetEmail($user, $plainPass);

                return $this->render('@App/SuccessPages/passReset.html.twig');
            }
            else{
                return $this->render('@App/Home/404.html.twig');
            }
        }
        return $this->render('@App/Security/forgotPassword.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/activation", name="resendActivation")
     */
    public function resendActivation(Request $request)
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $repository = $this->getDoctrine()->getRepository('AppBundle:User');
            /** @var User $user */
            $user = $repository->findOneBy(['username' => $form['username']->getData(),
            'email' => $form['email']->getData()]);

            if($user){
                if(!$user->isEnabled()){
                    $this->get('app.send_email')->registrationEmail($user);
                    return $this->render('@App/SuccessPages/activationResent.html.twig');
                }
                else{
                    $this->addFlash('error', 'This account is already activated!');
                }
            }
            else{
                $this->addFlash('error', 'Such user doesn\'t exist!');
            }
        }
        return $this->render('@App/Security/activationRequest.html.twig', ['form' => $form->createView()]);
    }
}
