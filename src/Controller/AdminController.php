<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN", message="Accès refusé")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/liste_utilisateurs", name="liste_utilisateurs")
     * @IsGranted("ROLE_ADMIN", message="Accès refusé")
     * @param UtilisateurRepository $userRepo
     * @return Response
     */
    public function index(UtilisateurRepository $userRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('admin/index.html.twig', [
            'utilisateurs' => $userRepo->findAll(),
        ]);
    }
}
