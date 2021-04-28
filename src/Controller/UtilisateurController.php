<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\DBAL\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
     * @Route("/new", name="utilisateur_new", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $form->get('password')->getData();
            $utilisateur->setPassword($encoder->encodePassword($utilisateur, $form->get('password')->getData()));

            $userTest = $utilisateurRepository->findByEmail($form->get('email')->getData());

            if ($userTest){
                $this->addFlash('danger', 'Utilisateur existe déjà');
                return $this->redirectToRoute('accueil');
            }

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur créé');
            return $this->redirectToRoute('accueil');
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="utilisateur_show", methods={"GET"})
     * @IsGranted("ROLE_USER", message="Accès refusé")
     */
    public function show(Utilisateur $utilisateur): Response
    {
        // Si c'est pas un admin
        if (!in_array('ROLE_ADMIN, ', $this->getUser()->getRoles())){
            // Si c'est pas le même user
            if ($this->getUser()->getUsername() != $utilisateur->getUsername()){
                $this->addFlash('success', 'Erreur : Utilisateur introuvable');
                return $this->redirectToRoute('accueil');
            }
        }

        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="utilisateur_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER", message="Accès refusé")
     * @param Request $request
     * @param Utilisateur $utilisateur
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function edit(Request $request, Utilisateur $utilisateur, UserPasswordEncoderInterface $encoder): Response
    {
        // Si c'est pas un admin
        if (!in_array('ROLE_ADMIN, ', $this->getUser()->getRoles())){
            // Si c'est pas le même user
            if ($this->getUser()->getUsername() != $utilisateur->getUsername()){
                $this->addFlash('success', 'Erreur : Utilisateur introuvable');
                return $this->redirectToRoute('accueil');
            }
        }

        $form = $this->createForm(UtilisateurType::class, $utilisateur);

        $form->add('password', PasswordType::class, [
            'mapped' => false,
            'required' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (strlen($form->get('password')->getData()) > 8) {
                $form->get('password')->getData();
                $utilisateur->setPassword($encoder->encodePassword($utilisateur, $form->get('password')->getData()));
                $this->addFlash('success', 'Mot de passe modifié');
            } else if (strlen($form->get('password')->getData()) > 0) {
                $this->addFlash('warning', 'Mot de passe inchangé. Le mot doit contenir 8 caractères minimum');
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Modification enregistré');
            return $this->redirectToRoute('utilisateur_show', ['id' => $utilisateur->getId()]);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateur_delete", methods={"POST"})
     */
    public function delete(Request $request, Utilisateur $utilisateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('utilisateur_index');
    }
}
