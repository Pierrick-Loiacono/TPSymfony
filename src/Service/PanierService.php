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


}