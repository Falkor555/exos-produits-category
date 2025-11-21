<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Moderateur;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UtilisateurFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Utilisateur simple
        $utilisateur = new Utilisateur();
        $utilisateur->setNom("Botsy");
        $utilisateur->setPrenom("Loic");
        $utilisateur->setAdresseMail("loic@gmail.com");
        $utilisateur->setMotDePasse("lol");
        $manager->persist($utilisateur);

        // Admin
        $admin = new Admin();
        $admin->setNom("Super");
        $admin->setPrenom("Admin");
        $admin->setAdresseMail("admin@gmail.com");
        $admin->setMotDePasse("admin");
        $admin->setStatus("ABS");
        $manager->persist($admin);

        // Modérateur
        $moderateur = new Moderateur();
        $moderateur->setNom("Le");
        $moderateur->setPrenom("Modo");
        $moderateur->setAdresseMail("mod@gmail.com");
        $moderateur->setMotDePasse("mod");
        $manager->persist($moderateur);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['utilisateurs'];
    }
}