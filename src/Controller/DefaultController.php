<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Seance;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="accueil", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $this->getUser();
        
        //Le premier array vide signifie qu'il n'y a pas une colonne particulier à trouver
        //Cependant, on triera les séances par id (soit des séances crées récemment).
        //Le chiffre 5 est une limite pour récupérer seulement les 5 séances en ordre décroissant 
        $seances = $entityManager->getRepository(Seance::class)->findBy([],['id'=>'DESC'],5);
        
        if($utilisateur){

            $joueur = $entityManager->getRepository(Joueur::class)->find($utilisateur->getId());
            $participation = $joueur->getParticipation();
            return $this->render('index.html.twig', [
                'seances' => $seances,
                'participation' => $participation,
            ]);
        }
        else{
            return $this->render('index.html.twig', [
                'seances' => $seances,
            ]);
        }
    }

    /**
     * @Route("/mes-seances-participation", name="seance_participation_perso", methods={"GET"})
     */
    public function mesSeancesParticipation(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $utilisateur = $this->getUser();

        if($utilisateur){
            //Le premier array vide signifie qu'il n'y a pas une colonne particulier à trouver
            //Cependant, on triera les séances par id (soit des séances crées récemment).
            //Le chiffre 5 est une limite pour récupérer seulement les 5 séances en ordre décroissant 
            $seancesPerso = $entityManager->getRepository(Seance::class)->findBy(['organisateur' => $utilisateur->getId()]);
            $pageSeances = $paginator->paginate(
                $seancesPerso,
                $request->query->getInt('page', 1),5
            );
            $joueur = $entityManager->getRepository(Joueur::class)->find($utilisateur->getId());
            $participation = $joueur->getParticipation();
            $pageParticipations = $paginator->paginate(
                $participation,
                $request->query->getInt('page', 1),5
            );

            return $this->render('seance_part_perso.html.twig', [
                'seances' => $pageSeances,
                'participations' => $pageParticipations,
            ]);
        }
        else{
            return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
        }
        

    }
 
    /**
    * @Route("/mentions-legales", name="mentions_legales", methods={"GET"})
    */
    public function mentionsLegales(): Response{
        return $this->render('commons/mentions_legales.html.twig');
    }

};