<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Seance;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function mesSeancesParticipation(EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $this->getUser();
        //Le premier array vide signifie qu'il n'y a pas une colonne particulier à trouver
        //Cependant, on triera les séances par id (soit des séances crées récemment).
        //Le chiffre 5 est une limite pour récupérer seulement les 5 séances en ordre décroissant 
        $seances = $entityManager->getRepository(Seance::class)->findBy(['organisateur' => $utilisateur->getId()]);
        $joueur = $entityManager->getRepository(Joueur::class)->find($utilisateur->getId());
        $participation = $joueur->getParticipation();

        return $this->render('seance_part_perso.html.twig', [
            'seances' => $seances,
            'participations' => $participation,
        ]);
        

    }

};