<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

}
