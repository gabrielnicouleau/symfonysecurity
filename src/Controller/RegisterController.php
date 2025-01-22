<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\RegisterType;
use Doctrine\DBAL\Driver\PDO\PDOException;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Driver\PDO\Exception;
use App\Service\EmailService;
use App\Service\UtilsService;
use SebastianBergmann\CodeCoverage\Report\Html\Renderer;

final class RegisterController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $em,
        private readonly EmailService $emailService
    ){}
    
    //Méthode pour ajouter un compte
    #[Route('/register', name: 'app_register')]
    public function addUser(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);
        
        $form->handleRequest($request);
        
        //Test si le formulaire est submit
        if($form->isSubmitted()&& $form->isValid()) {
            //Nettoyage des entrées
            $user->sanitizeUser(); 
            try {
                //Test si le compte n'existe pas
                if(!$this->userRepository->findOneBy(["email"=>$user->getEmail()])) {
                    $user->setActived(false);
                    $user->setRoles(["ROLE_USER"]);
                    $this->em->persist($user);
                    $this->em->flush();
                    $objet = "Activation de votre compte";
                    $body = $this->render('email/activation.html.twig', [
                        'id'=>base64_encode($user->getId())
                    ]);
                    $this->emailService->sendEmail($user->getEmail(),$objet,$body->getContent()); 
                    $type = "success";
                    $msg = "Le compte " . $user->getEmail() . " a été ajouté en BDD";    
                }
                //Sinon le compte existe déja
                else{
                    $type = "danger";
                    $msg = "Le compte existe déja";
                }
            } catch (PDOException $exception) {
                $type = "danger";
                $msg = "Erreur BDD";
            }
            $this->addFlash($type, $msg);
            return $this->redirectToRoute('app_home', [], 303);
        }
        return $this->render('register/adduser.html.twig', [
            'formulaire' => $form
        ]);
    }


    //Méthode pour activer un compte
    #[Route('/register/activate/{id}', name:'app_register_activate')]
    public function activeUser($id) : Response {
        $id = base64_decode($id);
        $user = $this->userRepository->find((int) $id);
        //Test si le compte existe
        if($user) {
            //Test si le compte n'est pas activé
            if(!$user->isActived()) {
                //Activer le compte
                $user->setActived(true);
                $this->em->persist($user);
                $this->em->flush();
                return new Response('Le compte a été activé avec succès', 200);
            }
            //Si le compte est déja activé
            return new Response('Le compte a déja été activé', 200);
        }
        //Le compte n'existe pas
        return new Response('Le compte n\'est pas valide', 400);
    }
}


