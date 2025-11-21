<?php

  namespace App\DataFixtures;

  use App\Entity\Produit;
//   use App\Entity\Category;

  use Doctrine\Bundle\FixturesBundle\Fixture;
  use Doctrine\Persistence\ObjectManager;
  use Faker;

 class AppFixtures extends Fixture
 {
     public function load(ObjectManager $manager): void
      {
       $faker = Faker\Factory::create('fr_FR');
           // on crée 10000 produits avec noms et prix "aléatoires" en français
           $produits = Array();
           for ($i = 0; $i < 10000; $i++) {
               $produits[$i] = new Produit();
               $produits[$i]->setNom($faker->product());
               $produits[$i]->setPrix($faker->randomFloat(2, 1, 99999.99));
               $manager->persist($produits[$i]);
           }

           $manager->flush();
       }
   }