<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ChangePassword;
use AppBundle\Entity\EditUser;
use AppBundle\Entity\User;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\EditUserType;
use AppBundle\Form\UserType;
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
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
      //  die($lastEmail);
        return $this->render('@App/SecurityController/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {
       /* $session = new Session();
        $session->getFlashBag()->add('success', 'Lauksime Jūsų sugrįžtant!');

        $response = new RedirectResponse($request->headers->get('referer'));

        return $response;*/
    }

    /**
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
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('app.send_email')->sendEmail($user);

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            '@App/SecurityController/register.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/paskyra/keisti", name="keistiDuomenis")
     */
    public function userEditAction(Request $request) {

        $securityContext = $this->get('security.authorization_checker');

        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY') ||
            $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            $user = $this->getUser();
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
            return $this->render('@App/SecurityController/editUser.html.twig', array(
                'form' => $form->createView()
            ));
        }
        else {
            return $this->render('@App/Home/index.html.twig');
        }

    }

    /**
     * @Route("/paskyra/slaptazodis", name="keistiSlaptazodi")
     */
    public function userPasswordAction(Request $request) {
        $securityContext = $this->get('security.authorization_checker');

        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY') ||
            $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            $newPass = new ChangePassword();

            $form = $this->createForm(ChangePasswordType::class, $newPass);

            $form->handleRequest($request);

            if($form->isSubmitted()) {

                if($form->isValid()) {

                    $user = $this->getUser();

                    $pass = $this->get('security.password_encoder')
                        ->encodePassword($user, $form['newPassword']->getData());

                    $user->setPassword($pass);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('success', 'Duomenys sėkmingai pakeisti!');
                }
            }
            return $this->render('@App/SecurityController/changePass.html.twig', array(
                'form' => $form->createView()
            ));
        }
        else {
            return $this->render('@App/Home/index.html.twig');
        }
    }

}
