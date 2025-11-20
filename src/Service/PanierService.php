<?php

namespace App\Service;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PanierService
{
    private $session;
    private $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->session = $requestStack->getSession();
        $this->em = $em;
    }

    public function add(int $id): void
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $this->session->set('panier', $panier);
    }

    public function remove(int $id): void
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
    }

    public function updateQuantity(int $id, int $quantite): void
    {
        $panier = $this->session->get('panier', []);

        if ($quantite <= 0) {
            $this->remove($id);
        } else {
            $panier[$id] = $quantite;
            $this->session->set('panier', $panier);
        }
    }

    public function getFullPanier(): array
    {
        $panier = $this->session->get('panier', []);
        $panierWithData = [];

        foreach ($panier as $id => $quantite) {
            $produit = $this->em->getRepository(Produit::class)->find($id);
            if ($produit) {
                $panierWithData[] = [
                    'produit' => $produit,
                    'quantite' => $quantite
                ];
            }
        }

        return $panierWithData;
    }

    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getFullPanier() as $item) {
            $total += (float)$item['produit']->getPrix() * $item['quantite'];
        }

        return $total;
    }

    public function clear(): void
    {
        $this->session->remove('panier');
    }

    public function getCount(): int
    {
        $panier = $this->session->get('panier', []);
        return array_sum($panier);
    }
}
