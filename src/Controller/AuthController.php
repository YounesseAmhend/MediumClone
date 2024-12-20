<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }
    #[Route('/login', name: 'app_user_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    #[Route('/new', name: 'app_user_register', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        

    }
    #[Route('/list', name: 'app_user_register', methods: ['GET', 'POST'])]
    public function list(): Response
    {
        return new JsonResponse("Student list");

    }

    #[Route('/logout', name: 'app_user_logout')]
    public function logout(): void
    {

    }
}
