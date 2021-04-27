<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/utilisateur")
 */
class UtilisateurController extends AbstractController
{

    /**
     * @Route("/", name="utilisateur_index", methods={"GET"})
     * @param UtilisateurRepository $utilisateurRepository
     * @return Response
     */

    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur/index.html.twig', [
//            'utilisateur' => $this->getDoctrine()->getRepository(Utilisateur::class)->findOneById($this->session->get('utilisateur')),
        ]);
    }

    /**
     * @Route("/new", name="utilisateur_new", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Encryptage du mot de passe
            $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur,$utilisateur->getPassword()));
            // Définition du rôle
            $utilisateur->setRoles(["ROLE_CLIENT"]);
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('utilisateur_index');
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/commande", name="commande", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function commandes(Request $request): Response
    {
        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findBy(['utilisateur' => $this->getUser()->getId()], ['date_commande' => 'DESC']);

        return $this->render('utilisateur/commande.html.twig', [
            'utilisateur' => $this->getUser(),
            'commandes' => $commandes,
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateur_show", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Utilisateur $utilisateur
     * @return Response
     */
    public function show(Request $request, UserPasswordEncoderInterface $passwordEncoder, Utilisateur $utilisateur): Response
    {
        $form_edit = $this->createForm(UtilisateurType::class, $utilisateur);
        $form_edit->remove('password');
        $form_edit->handleRequest($request);

        if ($form_edit->isSubmitted() && $form_edit->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Encryptage du mot de passe
            $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur,$utilisateur->getPassword()));
            // Définition du rôle
            $utilisateur->setRoles(["ROLE_CLIENT"]);
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('utilisateur_show', ['id' => $utilisateur->getId()]);
        }

        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form_edit->createView(),
        ]);
    }
}
