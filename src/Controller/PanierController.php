<?php


namespace App\Controller;

use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Entity\Produit;
use App\Entity\Utilisateur;
use App\Service\PanierService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/panier")
 */
class PanierController extends AbstractController
{

    /**
     * @Route("/", name="panier")
     * @param PanierService $panierService
     * @return Response
     */
    public function index(PanierService $panierService)
    {
        $panierWithItems = [];
        $panier = $panierService->getContenu();

        $nbProduit = $panierService->getNbProduct();
        $prixTotal = $panierService->getTotal();

        foreach ($panier as $id => $quantity) {
            $panierWithItems[] = [
                'item' =>$this->getDoctrine()->getRepository(Produit::class)->findOneById($id),
                'quantity' => $quantity];
        }
        // TODO api.exchangeratesapi.io/latest
        return $this->render('panier/index.html.twig', [
                "panier" => $panierWithItems,
                "prixTotal" => $prixTotal,
                "nbProduit" => $nbProduit,
            ]
        );
    }

    /**
     * @Route("/ajout/{produitId}", name="ajout_panier")
     * @param PanierService $panierService
     * @param $produitId
     * @return RedirectResponse
     */
    public function ajoutProduit(PanierService $panierService, $produitId)
    {
        $panierService->addProduct($produitId);
        //$this->addFlash('success', 'Produit ajouté au panier');
        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/retrait/{produitId}", name="retrait_panier")
     * @param PanierService $panierService
     * @param $produitId
     * @return RedirectResponse
     */
    public function retraitProduit(PanierService $panierService, $produitId)
    {
        $panierService->removeOneProduct($produitId);
        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/retirer_produit/{produitId}", name="retirer_produit_panier")
     * @param PanierService $panierService
     * @param $produitId
     * @return RedirectResponse
     */
    public function retirerProduit(PanierService $panierService, $produitId)
    {
        $panierService->removeAllOfOneProduct($produitId);
        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/vider_panier", name="vider_panier")
     * @param PanierService $panierService
     * @param $produitId
     * @return RedirectResponse
     */
    public function viderPanier(PanierService $panierService)
    {
        $panierService->removeAllProducts();
        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/panier_validation", name="panier_validation")
     * @param PanierService $panierService
     * @return RedirectResponse
     * @throws Exception
     */
    public function validation(PanierService $panierService){

        if(!$this->getUser()){
            return $this->redirectToRoute('utilisateur_index');
        }

        $panierService->panierToCommande($this->getUser());
        $this->addFlash('success', 'Commande effectuée');
        return $this->redirectToRoute('commande');

    }

    public function nombreProduitPanier(PanierService $panierService){
        return $this->render('panier/nombre_produit.html.twig', [
                'nbProduit' => $panierService->getNbProduct()
            ]);
    }

}