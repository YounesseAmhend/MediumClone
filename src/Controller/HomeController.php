<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Security $security, PostRepository $postRepository): Response
    {
        // Check if the user is authenticated
        if (!$security->getUser()) {
            // Redirect to login page if the user is not authenticated
            return $this->redirectToRoute('app_login');
        }

        // Fetch all posts from the repository
        $posts = $postRepository->findAll();

        // Render the view and pass the posts data
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
