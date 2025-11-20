<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Moderateur;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/utilisateur/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, UtilisateurRepository $utilisateurRepository): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $utilisateur = $utilisateurRepository->findOneBy(['adresseMail' => $email]);

            if ($utilisateur && $utilisateur->getMotDePasse() === $password) {
                // Stocker l'utilisateur en session
                $request->getSession()->set('utilisateur_id', $utilisateur->getId());
                $request->getSession()->set('utilisateur_nom', $utilisateur->getNom());
                $request->getSession()->set('utilisateur_prenom', $utilisateur->getPrenom());

                $request->getSession();

                $this->addFlash('success', 'Connexion réussie ! Bienvenue ' . $utilisateur->getPrenom());

                return $this->redirectToRoute('app_home');

            } else {
                $this->addFlash('error', 'Email ou mot de passe incorrect.');
            }
        }

        return $this->render('utilisateur/login.html.twig');
    }

    #[Route('/utilisateur/logout', name: 'app_logout')]
    public function logout(Request $request): Response
    {
        // Supprimer toutes les données de session
        $request->getSession()->invalidate();
        $this->addFlash('success', 'Vous avez été déconnecté avec succès.');

        return $this->redirectToRoute('app_login');
    }

    #[Route('utilisateur/signup', name: 'app_signup', methods: ['GET', 'POST'])]
    public function signup(Request $request, EntityManagerInterface $em, UtilisateurRepository $utilisateurRepository): Response
    {
        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            // Vérifier si l'email existe déjà
            $existingUser = $utilisateurRepository->findOneBy(['adresseMail' => $email]);
            if ($existingUser) {
                $this->addFlash('error', 'Cet email est déjà utilisé.');
                return $this->redirectToRoute('app_signup');
            }

            // Vérifier si les mots de passe correspondent
            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_signup');
            }

            // Créer un nouvel utilisateur
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($nom);
            $utilisateur->setPrenom($prenom);
            $utilisateur->setAdresseMail($email);
            $utilisateur->setMotDePasse($password); // En production, utiliser password_hash()

            $em->persist($utilisateur);
            $em->flush();

            $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('utilisateur/signup.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }
}
