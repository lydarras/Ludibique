<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Seance;
use App\Form\SeanceType;
use App\Repository\SeanceRepository;
use Knp\Component\Pager\PaginatorInterface;
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
     * @Route("/", name="toutes_les_seances", methods={"GET"})
     */
    public function seanceIndex(EntityManagerInterface $entityManager, Request $request,SeanceRepository $seanceRepository, PaginatorInterface $paginator): Response
    {

            //La variable $données récupère toutes les séances tandis que $séances permet de paginer toutes les séances
            //1 pour page 1 et 5 sur le nombre limite de séances par page
            $donnees = $entityManager->getRepository(Seance::class)->findAll();
            $seances = $paginator->paginate(
                $donnees,
                $request->query->getInt('page', 1),5
            );
            return $this->render('seance/index.html.twig', [
                'seances' => $seances,
            ]);

    }

    /**
     * @Route("/creerSeance", name="creation_seance", methods={"GET", "POST"})
     */
    public function creationSeance(Request $request, SeanceRepository $seanceRepository): Response
    {

        //La méthode getUser permet de récupérer des contenus de l'utilisateur connecté
        $user = $this->getUser();

        if($user){
            $seance = new Seance();
            $form = $this->createForm(SeanceType::class, $seance);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
    
                //L'organisateur attend comme valeur une entité d'un joueur. Donc on utilse $user pour mettre à jour l'organisateur, les données de l'utilisateur connecté
                $seance->setOrganisateur($user);
                $seanceRepository->add($seance, true);
    
                $this->addFlash('success',"La séance a été créée");

                return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
            }
            
            return $this->renderForm('seance/form_seance.html.twig', [
                'form' => $form,
            ]);     
         }

         else{
            return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
         }
    }

    /**
     * @Route("/{id}", name="affiche_seance", methods={"GET"})
     */
    public function afficheSeance(Seance $seance): Response
    {
        return $this->render('seance/affiche_seance.html.twig', [
            'seance' => $seance,
        ]);
    }

    /**
     * @Route("/{id}/modifier-seance", name="modifie_seance", methods={"GET", "POST"})
     */
    public function modifieSeance(Request $request, Seance $seance, SeanceRepository $seanceRepository): Response
    {

        $user = $this->getUser();

        if($user){
            $form = $this->createForm(SeanceType::class, $seance);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $seanceRepository->add($seance, true);
    
                $this->addFlash('success',"Séance mise à jour !");
    
                return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
            }
    
            return $this->renderForm('seance/form_seance.html.twig', [
                'seance' => $seance,
                'form' => $form,
            ]);
        }
        else{
            return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
         }
    }

    /**
     * @Route("/suppresionSeance/{id}", name="supprime_seance", methods={"GET", "POST"})
     */
    public function supprimeSeance(Request $request, Seance $seance, SeanceRepository $seanceRepository): Response
    {

        $user = $this->getUser();

        if($user){
            if ($this->isCsrfTokenValid('delete'.$seance->getId(), $request->request->get('_token'))) {
                $seanceRepository->remove($seance, true);
            }
            return $this->redirectToRoute('toutes_les_seances', [], Response::HTTP_SEE_OTHER);
        }
        else{
            return $this->redirectToRoute('toutes_les_seances', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/participation/{id}", name="ajouterJoueur", methods={"GET"})
     */
    public function ajouteJoueur(Seance $seance, SeanceRepository $seanceRepository): Response
    {
        //La méthode getUser permet de récupérer des contenus de l'utilisateur connecté
        $user = $this->getUser();

        if($user){
            $seance->addJoueur($user);
            $seanceRepository->add($seance, true);
            return $this->redirectToRoute('seance_participation_perso', [], Response::HTTP_SEE_OTHER);
        }
        else{
            return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
         }
    }

    /**
     * @Route("/retrait/{id}", name="supprimerJoueur", methods={"GET"})
     */
    public function supprimerJoueur(Seance $seance, SeanceRepository $seanceRepository): Response
    {
        //La méthode getUser permet de récupérer des contenus de l'utilisateur connecté
        $user = $this->getUser();
        if($user){
            $seance->removeJoueur($user);
            $seanceRepository->add($seance, true);
            return $this->redirectToRoute('seance_participation_perso', [], Response::HTTP_SEE_OTHER);
        }
        else{
            return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
        }
    }
}
