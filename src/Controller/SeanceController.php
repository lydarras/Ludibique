<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Seance;
use App\Form\SeanceType;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/seance")
 */
class SeanceController extends AbstractController
{

    /**
     * @Route("/", name="app_seance_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager, SeanceRepository $seanceRepository): Response
    {

        $utilisateur = $this->getUser();
        $id = $utilisateur->getId();

        $seances = $entityManager->getRepository(Seance::class)->findBy(['organisateur' => $id]);

        return $this->render('seance/index.html.twig', [
            'seances' => $seances,
        ]);
    }

    /**
     * @Route("/new", name="app_seance_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SeanceRepository $seanceRepository): Response
    {
        $seance = new Seance();
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        //La méthode getUser permet de récupérer des contenus de l'utilisateur connecté
        $user = $this->getUser();


        if ($form->isSubmitted() && $form->isValid()) {

            //L'organisateur attend comme valeur une entité d'un joueur. Donc on utilse $user pour mettre à jour l'organisateur, les données de l'utilisateur connecté
            $seance->setOrganisateur($user);
            //dd($form);
            $seanceRepository->add($seance, true);
            return $this->redirectToRoute('app_seance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('seance/new.html.twig', [
            'seance' => $seance,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_seance_show", methods={"GET"})
     */
    public function show(Seance $seance): Response
    {
        return $this->render('seance/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_seance_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Seance $seance, SeanceRepository $seanceRepository): Response
    {
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seanceRepository->add($seance, true);

            return $this->redirectToRoute('app_seance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('seance/edit.html.twig', [
            'seance' => $seance,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_seance_delete", methods={"POST"})
     */
    public function delete(Request $request, Seance $seance, SeanceRepository $seanceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seance->getId(), $request->request->get('_token'))) {
            $seanceRepository->remove($seance, true);
        }

        return $this->redirectToRoute('app_seance_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/participation/{id}", name="ajouterJoueur", methods={"GET"})
     */
    public function ajouteJoueur(Seance $seance, SeanceRepository $seanceRepository): Response
    {
        //La méthode getUser permet de récupérer des contenus de l'utilisateur connecté
        $user = $this->getUser();
        $seance->addJoueur($user);
        $seanceRepository->add($seance, true);
        return $this->redirectToRoute('seance_participation_perso', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/retrait/{id}", name="supprimerJoueur", methods={"GET"})
     */
    public function supprimerJoueur(Seance $seance, SeanceRepository $seanceRepository): Response
    {
        //La méthode getUser permet de récupérer des contenus de l'utilisateur connecté
        $user = $this->getUser();
        $seance->removeJoueur($user);
        $seanceRepository->add($seance, true);
        return $this->redirectToRoute('seance_participation_perso', [], Response::HTTP_SEE_OTHER);
    }
}
