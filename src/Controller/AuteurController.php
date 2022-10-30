<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Form\AuteurType;
use App\Repository\AuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auteur")
 */
class AuteurController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="index_auteur", methods={"GET"})
     */
    public function indexAuteur(EntityManagerInterface $entityManager): Response
    {
        $auteurs = $entityManager->getRepository(Auteur::class)->findAll();
        return $this->render('admin/entite/auteur/index.html.twig', [
            'auteurs' => $auteurs
        ]);
    }

    /**
     * @Route("/nouveauAuteur", name="creer_auteur", methods={"GET", "POST"})
     */
    public function creerAuteur(Request $request): Response
    {
        $auteur = new Auteur();
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($auteur);
            $this->entityManager->flush();
            $this->addFlash('success',"L'auteur a été crée");

            return $this->redirectToRoute('index_auteur');
        }

        return $this->render('admin/entite/auteur/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/afficher_auteur/{id}", name="app_auteur_show", methods={"GET"})
     */
    public function show(Auteur $auteur): Response
    {
        return $this->render('admin/entite/auteur/show.html.twig', [
            'auteur' => $auteur,
        ]);
    }

    /**
     * @Route("/modifier_auteur/{id}", name="modifier_auteur", methods={"GET", "POST"})
     */
    public function modifierAuteur(Request $request, Auteur $auteur): Response
    {
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($auteur);
            $this->entityManager->flush();
            $this->addFlash('success',"L'auteur a été édité");
            return $this->redirectToRoute('index_auteur', [], Response::HTTP_SEE_OTHER);

        }
            
        return $this->render('admin/entite/auteur/form.html.twig', [
            'form' => $form->createView(),
            'auteur' => $auteur
        ]);

    
    }

    /**
     * @Route("/supprimer_auteur/{id}", name="supprimer_auteur", methods={"GET"})
     */
    public function supprimerAuteur(Auteur $auteur): Response
    {
        $this->entityManager->remove($auteur);
        $this->entityManager->flush();

        return $this->redirectToRoute('index_auteur');
    }
}
