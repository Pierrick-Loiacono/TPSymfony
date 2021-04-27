<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/boutique")
 */
class BoutiqueController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function categorie(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();

        return $this->render('boutique/index.html.twig', [
            'nombreCategorie' => sizeof($categories),
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/categorie/{id}", name="produits")
     */
    public function produits($id): Response
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findByCategorie($id);

        return $this->render('boutique/produits.html.twig', [
            'nombreProduits' => sizeof($produits),
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/recherche", name="recherche")
     * @param Request $request
     * @return Response
     */
    public function rechercher(Request $request)
    {
        $texte = $request->get('recherche');
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findRechercheProduit($texte);
        return $this->render('boutique/produits.html.twig', [
            "produits" => $produits,
            "nombreProduit" =>sizeof($produits),
        ]);
    }

}
