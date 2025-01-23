<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ApiController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ArticleRepository $articleRepository
    ) {}
    
    #[Route('/api/all', name:'app_all_user')]
    public function getAllUser() :Response {
        return $this->json($this->userRepository->findAll(),200, [
            "Access-Control-Allow-Origin"=> "*",
            "Content-Type"=>"application/json"
        ], ['groups'=>"user:readAll"]);
    }

    #[Route('/api/articleall', name:'app_all_article')]
    public function getAllArticle() :Response {
        return $this->json($this->articleRepository->findAll(),200,[
            "Access-Control-Allow-Origin"=> "*",
            "Content-Type"=>"application/json"
        ], ['groups'=>'article:readAll']);
    }

}