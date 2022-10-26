<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('home');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/profil/editPass", name="user_editPassword")
     * @IsGranted("ROLE_USER", message="No access! Get out!")
     */
    public function editUserPassword(Request $request, UserRepository $repo, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager )
    {
        $user = $this->getUser();
        $userPassword = $user->getPassword();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->remove('pseudo');
        $form->remove('email');
        $form->remove('agreeTerms');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repo->upgradePassword($user, $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
                )
            );

            $this->addFlash('success', 'Mot de passe modifié');
            
            return $this->redirectToRoute('app_logout');

        }
        return $this->render('user/editPassword.html.twig', [
            'editForm' => $form->createView(),
        ]);
    
    }

    /**
    * @Route("/profil/delete", name="user_delete")
    * @IsGranted("ROLE_USER", message="No access! Get out!")
    */
    public function deleteUser( EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user)
        {
            $session = $this->get('session');
            $session = new Session();
            $session->invalidate();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute("home");
    }
}
