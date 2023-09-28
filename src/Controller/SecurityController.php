<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        
    }

    public function isUsernameValid($user)
    {
        $existingUser = $this->userRepository->findOneBy(['username' => $user->getUsername()]);

        if ($existingUser->getUsername() == $user->getUsername()) {
            // Gérez l'erreur, par exemple en renvoyant un message d'erreur à l'utilisateur
            $this->addFlash('danger', 'Ce nom d\'utilisateur est déjà utilisé.');
            // Redirigez l'utilisateur vers le formulaire d'inscription     
            return $this->redirectToRoute('app_register');
        }
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request,UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();      

            $existingUser = $this->userRepository->findOneBy(['username' => $user->getUsername()]);

            if ($existingUser) {
                // Gérez l'erreur, par exemple en renvoyant un message d'erreur à l'utilisateur
                $this->addFlash('danger', 'Ce nom d\'utilisateur est déjà utilisé.');
                // Redirigez l'utilisateur vers le formulaire d'inscription     
                return $this->redirectToRoute('app_register');
            }

            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success', 
                'Votre compte a bien été créé.'
            );

            return $this->redirectToRoute(('app_home'));
        }
        return $this->render('security/register.html.twig', [
            'form' =>  $form->createView()
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('target_path');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $this->addFlash(
            'success', 
            'Connexion réussie.'
        );

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
