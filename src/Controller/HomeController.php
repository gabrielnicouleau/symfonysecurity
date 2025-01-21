<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\EmailService;
use App\Service\UtilsService;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
          
        ]);
    }

    #[Route('/test', name:'app_home_test')]
    public function testSmtp(EmailService $emailService) :Response {
        $objet = "Mail pour tester la méthode sendEmail";
        
        $corps = "Ceci est un email pour tester la méthode sendEmail de mon projet";
        $destinataire = "nicouleau_gabriel@outlook.com";
        $body = $this->render('email/modele.html.twig', [
            'subject' => $objet,
            'body' => $corps,
            'sender' => $emailService->getUserEmail()
        ]);
        $emailService->sendEmail($destinataire, $objet,$body->getContent());
        return new Response();
    }
}