<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Moderateur;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur/create', name: 'app_utilisateur')]
    public function index(EntityManagerInterface $em): Response
    {
        $utilisateur=new Utilisateur();
        $utilisateur->setNom("Botsy");
        $utilisateur->setPrenom("Loic");
        $utilisateur->setAdresseMail("loic@gmail.com");
        $utilisateur->setMotDePasse("lol");

        $admin = new Admin();
        $admin->setNom("Super");
        $admin->setPrenom("Admin");
        $admin->setAdresseMail("admin@gmail.com");
        $admin->setMotDePasse("admin");
        $admin->setStatus("ABS");

        $moderateur=new Moderateur();
        $moderateur->setNom("Le");
        $moderateur->setPrenom("Modo");
        $moderateur->setAdresseMail("mod@gmail.com");
        $moderateur->setMotDePasse("mod");

        $em->persist($utilisateur);

        $em->persist($moderateur);
        $em->persist($admin);

        $em->flush();

        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    #[Route('/utilisateur/login', name: 'app_login', methods: ['GET'])]
    public function login(): Response
    {
        return $this->render('utilisateur/login.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    #[Route('/utilisateur/logout', name: 'app_logout')]
    public function logout(): void
    {

    }

    #[Route('utilisateur/signup', name: 'app_signup')]
    public function signup(): Response
    {
        return $this->render('utilisateur/signup.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

}
