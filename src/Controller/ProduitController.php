<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Produit;
use App\Entity\Tag;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/produit')]
final class ProduitController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route(name: 'app_produit', methods: ['GET'])]
    public function index(Request $request, ProduitRepository $produitRepository, PaginatorInterface $paginator): Response
    {
        // Récupérer la requête (query) pour tous les produits
        $query = $produitRepository->createQueryBuilder('p')
            ->orderBy('p.dateCreation', 'DESC')
            ->getQuery();

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Numéro de page (défaut: 1)
            20 // Nombre d'éléments par page
        );

        return $this->render('produit/index.html.twig', [
            'produits' => $pagination,
        ]);
    }

    #[Route('/produits.json', name: 'app_produits_json')]
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

    // #[Route('/catalogue', name: 'app_catalogue')]
    // public function catalogue(): Response
    // {
    //     $produits = $this->entityManager->getRepository(Produit::class)->findBy(['isActive' => true]);

    //     return $this->render('produit/catalogue.html.twig', [
    //         'produits' => $produits,
    //     ]);
    // }

    #[Route("/produits/new", name: 'app_produit_new')]
    public function create(Request $request): Response
    {
        $produit = new Produit();

        $formulaire = $this->createForm(ProduitType::class, $produit);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $produitFormulaire = $formulaire->getData();
            $produitFormulaire->setIsActive(true);
            $produitFormulaire->setDateCreation(new \DateTime());

            $this->entityManager->persist($produitFormulaire);
            $this->entityManager->flush();

            $this->addFlash('success', 'Produit créé avec succès !');

            return $this->redirectToRoute('app_produit');
        }

        return $this->render('produit/new.html.twig', [
            "form" => $formulaire->createView()
        ]);
    }

    #[Route('/produits/{id}', name: 'app_produit_show', requirements: ['id' => '\d+'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/produits/{id}/edit', name: 'app_produit_edit')]
    public function edit(Request $request, Produit $produit): Response
    {
        $formulaire = $this->createForm(ProduitType::class, $produit);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Produit modifié avec succès !');

            return $this->redirectToRoute('app_produit');
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $formulaire->createView(),
        ]);
    }

    #[Route('/produits/{id}/delete', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($produit);
            $this->entityManager->flush();

            $this->addFlash('success', 'Produit supprimé avec succès !');
        }

        return $this->redirectToRoute('app_produit');
    }

    #[Route('/test', name: 'app_produit_test')]
    public function test(): Response
    {
        $category = new Category();
        $category->setNom("Electronique");

        $produit1 = new Produit();
        $produit1->setNom("Laptop");
        $produit1->setPrix("999.99");
        $produit1->setDescription("Ordinateur portable haute performance");
        $produit1->setDateCreation(new \DateTime());
        $produit1->setIsActive(true);

        $produit2 = new Produit();
        $produit2->setNom("Monitor");
        $produit2->setPrix("299.99");
        $produit2->setDescription("Écran 27 pouces Full HD");
        $produit2->setDateCreation(new \DateTime());
        $produit2->setIsActive(true);

        $tag1 = new Tag();
        $tag1->setNom("Informatique");
        $tag2 = new Tag();
        $tag2->setNom("Black Friday");

        $produit1->addTag($tag1);
        $produit1->addTag($tag2);
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

        $this->addFlash('success', 'Données de test créées avec succès !');

        return $this->redirectToRoute('app_produit');
    }
}
