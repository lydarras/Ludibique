<?php

namespace App\Controller;

use App\Entity\JeuSociete;
use App\Form\JeuSocieteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/jeu_societe")
 */
class JeuSocieteController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="index_jeu_societe")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $jeuxsociete = $entityManager->getRepository(JeuSociete::class)->findAll();
        //dd($jeuxsociete);
        return $this->render('admin/entite/jeu_societe/index.html.twig', [
            'jeux_societe' => $jeuxsociete
        ]);
    }

    /**
     * @Route("/nouveauJeu", name="creer_jeu", methods={"GET", "POST"})
     */
    public function creerJeu(Request $request, SluggerInterface $slugger): Response
    {
        $editeur = new JeuSociete();
        $form = $this->createForm(JeuSocieteType::class, $editeur);
        $form->handleRequest($request);

        // Récupération des valeurs du nombre min et max de joueur du formulaire
        $joueurMin = $form['nb_joueur_min']->getData();
        $joueurMax = $form['nb_joueur_max']->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('image')->getData();

            if($file){

                $extension = ".".$file->guessExtension();

                $originalFilename = pathinfo($file->getClientOriginalName());
                $safeFilename = $slugger->slug($editeur->getNom());
                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {

                    $file->move($this->getParameter('photo_jeux'), $newFilename);
                    $editeur->setImage($newFilename);

                } catch (FileException $exception) {
                }

            }
            
            if($joueurMin <= $joueurMax){
                //dd($form);
                $this->entityManager->persist($editeur);
                $this->entityManager->flush();
                $this->addFlash('success',"Le jeu a été crée");
                return $this->redirectToRoute('index_jeu_societe');
            }
            else{
                return $this->render('admin/entite/jeu_societe/form.html.twig', [
                    'form' => $form->createView(),
                    'erreur_joueur' => "Nbmin < Nbmax exigé"
                ]);
            }
        }

        else{
            return $this->render('admin/entite/jeu_societe/form.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/afficher_jeu/{id}", name="afficher_jeu", methods={"GET"})
     */
    public function afficheJeu(JeuSociete $editeur): Response
    {
        return $this->render('admin/entite/jeu_societe/affiche.html.twig', [
            'jeu_societe' => $editeur,
        ]);
    }

    /**
     * @Route("/modifier_jeu/{id}", name="modifier_jeu", methods={"GET", "POST"})
     */
    public function modifierJeu(Request $request, JeuSociete $editeur, SluggerInterface $slugger): Response
    {

        $originalLogo= $editeur->getImage() ?? '' ;
        $form = $this->createForm(JeuSocieteType::class, $editeur,[
            'image' => $originalLogo
        ]);
        $form->handleRequest($request);

        $joueurMin = $form['nb_joueur_min']->getData();
        $joueurMax = $form['nb_joueur_max']->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('image')->getData();

            if($file){
                $extension = ".".$file->guessExtension();

                $originalFilename = pathinfo($file->getClientOriginalName());

                $safeFilename = $slugger->slug($editeur->getNom());

                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {

                    $file->move($this->getParameter('uploads_dir'), $newFilename);
                    $editeur->setImage($newFilename);

                } catch (FileException $exception) {
                    
                }

            } else {
                $editeur->setImage($originalLogo);
            }

            if($joueurMin <= $joueurMax){
                $this->entityManager->persist($editeur);
                $this->entityManager->flush();
                $this->addFlash('success',"Le jeu a été édité");
                //dd($editeur);
                return $this->redirectToRoute('index_jeu_societe');
            }
            else{
                return $this->render('admin/entite/jeu_societe/form.html.twig', [
                    'form' => $form->createView(),
                    'jeu_societe' => $editeur,
                    'erreur_joueur' => "Nbmin < Nbmax exigé"
                ]);
            }

        }
            
        else{
            return $this->render('admin/entite/jeu_societe/form.html.twig', [
                'form' => $form->createView(),
                'jeu_societe' => $editeur
            ]);
        }

    
    }

    /**
     * @Route("/supprimer_jeu/{id}", name="supprimer_jeu", methods={"GET"})
     */
    public function supprimerJeu(JeuSociete $editeur): Response
    {
        $this->entityManager->remove($editeur);
        $this->entityManager->flush();

        return $this->redirectToRoute('index_jeu_societe');
    }

}
