<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Produit;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProduitController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    #[Route('/produits.json', name: 'app_produits')]
    public function getAllJson(): Response
    {
        return $this->json($this->entityManager->getRepository(Produit::class)->findAll());
    }

    #[Route('/produits', name: 'app_produits')]
    public function getAll(): Response
    {
        return $this->render('produit/liste.html.twig', [
            'produits' => $this->entityManager->getRepository(Produit::class)->findAll(),
        ]);
    }

    #[Route("/produits/create", name: 'app_produit_create')]
    public function create(Request $request): Response
    {
        $produit= new Produit();

        $formBuilder=$this->createFormBuilder( $produit);

        $formBuilder->add('nom', TextType::class)
            ->add('prix', MoneyType::class)
            ->add('category', EntityType::class, ['class'=>Category::class])
            ->add('submit', SubmitType::class);

        $formulaire=$formBuilder->getForm();
        $formulaire->handleRequest($request);
        if( $formulaire->isSubmitted() ){
            var_dump("Le formulaire a été soumis");
            $produitFormulaire=$formulaire->getData();
            $produitFormulaire->setIsActive(true);
            $produitFormulaire->setDateCreation(new \DateTime());


            $this->entityManager->persist($produitFormulaire);
            $this->entityManager->flush();
        }
        else{
            var_dump("Le formulaire est tout neuf");
        }

        return $this->render('produit/create.html.twig', [
            "truc" => $formulaire
        ]);
    }
    #[Route('/test', name: 'app_produit_test')]
    public function test(): Response
    {
        $category=new Category();
        $category->setNom("Electronique");

        $produit1=new Produit();
        $produit1->setNom("Laptop");
        $produit1->setPrix("10");
        $produit1->setDateCreation(new \DateTime());
        $produit1->setIsActive(true);

        $produit2=new Produit();
        $produit2->setNom("Monitor");
        $produit2->setPrix("20");
        $produit2->setDateCreation(new \DateTime());
        $produit2->setIsActive(true);

        $tag1=new Tag();
        $tag1->setNom("Informatique");
        $tag2=new Tag();
        $tag2->setNom("Black firday");

        $produit1->addTag($tag1);
        $produit2->addTag($tag2);
        $produit1->setCategory($category);

        $produit2->setCategory($category);
        $produit2->addTag($tag1);

        $this->entityManager->persist($category);
        $this->entityManager->persist($tag1);
        $this->entityManager->persist($tag2);

        $this->entityManager->persist($produit1);
        $this->entityManager->persist($produit2);

        $this->entityManager->flush();
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
}
