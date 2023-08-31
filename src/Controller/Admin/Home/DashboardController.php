<?php

namespace App\Controller\Admin\Home;

use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\ContactRepository;
use App\Repository\PostLikeRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/home/dashboard', name: 'admin.home.dashboard')]
    public function index(ContactRepository $contactRepository, PostRepository $postRepository, TagRepository $tagRepository, UserRepository $userRepository, CommentRepository $commentRepository, CategoryRepository $categoryRepository, PostLikeRepository $postLikeRepository): Response
    {
        return $this->render('pages/admin/home/index.html.twig', [
            'contact' => $contactRepository->findAll(),
            'post' => $postRepository->findAll(),
            'tag' => $tagRepository->findAll(),
            'user' => $userRepository->findAll(),
            'comment' => $commentRepository->findAll(),
            'category' => $categoryRepository->findAll(),
            'postLike' => $postLikeRepository->findAll(),
        ]);
    }
}
