<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ChangePassword;
use AppBundle\Entity\EditUser;
use AppBundle\Entity\User;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\EditUserType;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $securityContext = $this->get('security.authorization_checker');

        if(!$securityContext->isGranted('IS_AUTHENTICATED_FULLY') ||
                !$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $authenticationUtils = $this->get('security.authentication_utils');

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();

            // last username entered by the user
            $lastEmail = $authenticationUtils->getLastUsername();
            //  die($lastEmail);
            return $this->render('@App/Security/login.html.twig', [
                'last_email' => $lastEmail,
                'error'         => $error,
            ]);
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        $securityContext = $this->get('security.authorization_checker');

        if(!$securityContext->isGranted('IS_AUTHENTICATED_FULLY') ||
            !$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $user = new User();
            $form = $this->createForm(UserType::class, $user);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPassword());
                $user->setPassword($password);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->get('app.send_email')->sendEmail($user);

                return $this->redirectToRoute('homepage');
            }

            return $this->render(
                '@App/Security/register.html.twig',
                ['form' => $form->createView()]
            );
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/profile/edit", name="keistiDuomenis")
     */
    public function userEditAction(Request $request) {
        $securityContext = $this->get('security.authorization_checker');
        $user = $this->getUser();

        if(($securityContext->isGranted('IS_AUTHENTICATED_FULLY') ||
            $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) && !$user->getFacebookId()) {

            $edit = new EditUser($user);

            $form = $this->createForm(EditUserType::class, $edit);
            $form->handleRequest($request);

            if($form->isSubmitted()) {

                if($form->isValid()) {

                    $user->editUser($edit);

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
        else {
            return $this->redirectToRoute('homepage');
        }

    }

    /**
     * @Route("/profile/password", name="keistiSlaptazodi")
     * @Security("has_role('ROLE_USER')")
     *
     */
    public function userPasswordAction(Request $request) {
        $securityContext = $this->get('security.authorization_checker');
        $user = $this->getUser();

        if(($securityContext->isGranted('IS_AUTHENTICATED_FULLY') ||
            $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) && !$user->getFacebookId()) {

            $newPass = new ChangePassword();

            $form = $this->createForm(ChangePasswordType::class, $newPass);

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
     * @Route("/connect/facebook", name="fbConnect")
     */
    public function connectAction()
    {
        // will redirect to Facebook!
        return $this->get('oauth2.registry')
            ->getClient('facebook') // key used in config.yml
            ->redirect();
    }

    /**
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     */
    public function connectCheckAction(Request $request)
    {
        return $this->redirectToRoute('homepage');
    }

}
