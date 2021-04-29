<?php

namespace App\Service;

use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Entity\Produit;
use App\Entity\Utilisateur;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{

    const PANIER_SESSION = 'panier';
    /**
     * @var SessionInterface $session
     */
    private $session;
    /**
     * @var ProduitRepository $repo
     */
    private $repo;
    private $panier;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     *
     * @param SessionInterface $session
     * @param ProduitRepository $repo
     * @param EntityManagerInterface $em
     */
    public function __construct(SessionInterface $session, ProduitRepository $repo, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->repo = $repo;
        $this->panier = $this->session->get(self::PANIER_SESSION, []);
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getContenu(): array
    {
        return $this->session->get(self::PANIER_SESSION, []);
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getContenu() as $id => $quantity) {
            $total += $this->repo->findOneById($id)->getPrix() * $quantity;
        }
        return $total;
    }

    public function getNbProduct(){
        //Retourne le nombre de produit
        $nbProduct = 0;
        foreach ($this->getContenu() as $quantite){
            $nbProduct +=1;
        }
        return $nbProduct;
    }

    public function addProduct(int $productId, int $quantite = 1)
    {
        if (isset($this->panier[$productId])) {
            $this->panier[$productId] += $quantite;
        } else {
            $this->panier[$productId] = $quantite;
        }

        $this->session->set(self::PANIER_SESSION, $this->panier);
    }

    public function removeOneProduct(int $productId, int $quantite = 1)
    {
        if (isset($this->panier[$productId]) && $this->panier[$productId] > $quantite) {
            $this->panier[$productId] -= $quantite;
        } else {
            unset($this->panier[$productId]);
        }
        $this->session->set(self::PANIER_SESSION, $this->panier);
    }

    public function removeAllOfOneProduct(int $productId)
    {
        unset($this->panier[$productId]);
        $this->session->set(self::PANIER_SESSION, $this->panier);
    }

    public function removeAllProducts()
    {
        //$this->session->clear();
        $this->panier = [];
        $this->session->set(self::PANIER_SESSION, $this->panier);
    }


    public function panierToCommande(Utilisateur $utilisateur)
    {
        $commande = new Commande();
        $commande->setUtilisateur($utilisateur);
        $commande->setDateCommande(new \DateTime());
        $commande->setStatut("En attente de confirmation");

        foreach ($this->panier as $produitId => $quantite){
            $p = $this->repo->findOneById($produitId);
            $ligne = new LigneCommande();
            $ligne->setCommande($commande);
            $ligne->setProduit($p);
            $ligne->setQuantite($quantite);
            $ligne->setPrix($p->getPrix());
            $this->em->persist($ligne);
        }
        $this->em->persist($commande);
        $this->em->flush();
        $this->removeAllProducts();

    }
}