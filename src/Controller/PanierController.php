<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Service\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panier')]
class PanierController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'app_panier_index')]
    public function index(PanierService $panierService): Response
    {
        return $this->render('panier/index.html.twig', [
            'items' => $panierService->getFullPanier(),
            'total' => $panierService->getTotal()
        ]);
    }

    #[Route('/add/{id}', name: 'app_panier_add')]
    public function add(int $id, PanierService $panierService): Response
    {
        $panierService->add($id);
        $this->addFlash('success', 'Produit ajouté au panier !');

        return $this->redirectToRoute('app_catalogue');
    }

    #[Route('/remove/{id}', name: 'app_panier_remove')]
    public function remove(int $id, PanierService $panierService): Response
    {
        $panierService->remove($id);
        $this->addFlash('info', 'Produit retiré du panier.');

        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/valider', name: 'app_panier_valider')]
    public function valider(PanierService $panierService): Response
    {
        $panierItems = $panierService->getFullPanier();

        if (empty($panierItems)) {
            $this->addFlash('warning', 'Votre panier est vide !');
            return $this->redirectToRoute('app_panier_index');
        }

        $commande = new Commande();
        $commande->setDateCommande(new \DateTime());
        $commande->setStatut('confirmee');
        $commande->setTotal((string)$panierService->getTotal());

        foreach ($panierItems as $item) {
            $ligneCommande = new LigneCommande();
            $ligneCommande->setProduit($item['produit']);
            $ligneCommande->setQuantite($item['quantite']);
            $ligneCommande->setPrixUnitaire($item['produit']->getPrix());
            $ligneCommande->setCommande($commande);

            $commande->addLignesCommande($ligneCommande);
        }

        $this->entityManager->persist($commande);
        $this->entityManager->flush();

        $panierService->clear();

        $this->addFlash('success', 'Votre commande a été validée avec succès !');

        return $this->redirectToRoute('app_commande_confirmation', ['id' => $commande->getId()]);
    }

    #[Route('/clear', name: 'app_panier_clear')]
    public function clear(PanierService $panierService): Response
    {
        $panierService->clear();
        $this->addFlash('info', 'Panier vidé.');

        return $this->redirectToRoute('app_panier_index');
    }
}
