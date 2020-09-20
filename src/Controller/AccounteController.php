<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccuntType;
use App\Form\PasswordUpdatType;
use App\Form\RegistrationType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccounteController extends AbstractController
{
    /**
     * @Route("/login", name="accounte_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        return $this->render('accounte/login.html.twig', [
            'hasError' => $error !== null,
            'userName' => $username
        ]);
    }

    /**
     * @Route("/logout", name="accounte_logout")
     * @return void
     */
    public function logout(){
    }

    /**
     * @Route("/register", name="accounte_register")
     * @return Response
     */

    public function register(Request $request, UserPasswordEncoderInterface $encoder) {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getHash());

            $entityManager = $this->getDoctrine()->getManager();
            $user->setHash($hash);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'votre compt a bien ètè crèè ! vous pouvez vous connecter '
            );
            return $this->redirectToRoute("accounte_login");
        }
        return $this->render('accounte/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile", name="accounte_profile")
     * @return Response
     */
    public function profile(Request $request) {
        $user = $this->getUser();
        $form = $this->createForm(AccuntType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'votre compt a bien ètè modifier '
            );
            return $this->redirectToRoute("home");
        }
        return $this->render('accounte/profile.html.twig', [
        'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/update-password", name="accounte_password")
     * @return Response
     */
    public function ubdatePassword(Request $request , UserPasswordEncoderInterface $encoder) {
        $passwordUpdat = new PasswordUpdate();
        $form = $this->createForm(PasswordUpdatType::class, $passwordUpdat);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $this->getUser();
            if (!password_verify($passwordUpdat->getOldPassword(), $user->getHash()))
            {
                $form->get('oldPassword')->addError(new FormError("le mot de pass que vous avez taper n'est pas votre mot de pass actuele "));
                $this->addFlash(
                    'danger',
                    "votre mot de pass n'a pas ètè modifier "
                );
            } else {

                $newPassword = $passwordUpdat->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $entityManager = $this->getDoctrine()->getManager();
                $user->setHash($hash);
                $entityManager->persist($user);

                $entityManager->flush();
                $this->addFlash(
                    'success',
                    'votre mot de pass a bien ètè modifier '
                );
                return $this->redirectToRoute("home");
             }
            }


        return $this->render('accounte/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/accunte", name="accounte_myIndex")
     * @return Response
     */
    public function myAccount()
    {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
