<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        // Liste de noms de produits
        $typeProduits = [
            'Smartphone', 'Ordinateur portable', 'Tablette', 'Écouteurs', 'Montre connectée',
            'Lampe', 'Chaise', 'Table', 'Canapé', 'Coussin', 'Tapis', 'Miroir',
            'Mixeur', 'Cafetière', 'Grille-pain', 'Bouilloire', 'Robot cuiseur',
            'Ballon', 'Raquette', 'Vélo', 'Tapis de yoga', 'Haltères',
            'T-shirt', 'Jean', 'Robe', 'Veste', 'Chaussures', 'Sac à main',
            'Parfum', 'Crème', 'Shampoing', 'Brosse',
            'Roman', 'BD', 'Manga', 'Peluche', 'Puzzle', 'Jeu de société'
        ];

        $marques = ['Premium', 'Deluxe', 'Classic', 'Modern', 'Vintage', 'Pro', 'Elite'];

        $produits = Array();
        for ($i = 0; $i < 10000; $i++) {
            $produits[$i] = new Produit();

            // Nom composé : Type + Marque
            $nom = $faker->randomElement($typeProduits) . ' ' . $faker->randomElement($marques);
            $produits[$i]->setNom($nom);

            $produits[$i]->setPrix($faker->randomFloat(2, 1, 99999.99));
            $manager->persist($produits[$i]);

            $produits[$i]->setDescription($faker->sentence(10));
            $produits[$i]->setDateCreation(new \DateTime());
            $produits[$i]->setIsActive(true);
        }

        $manager->flush();
    }
}
