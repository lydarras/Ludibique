<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class JoueurController extends AbstractController
{
    /**
     * @Route("/inscription", name="creer_joueur")
     */
    public function inscrire(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Joueur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles(["ROLE_USER"]);
            $user->setAvatar("unknown.png");

            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->addFlash('success',"Inscription réussi. Vous pouvez à présent vous connecter !");

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('joueur/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/mon-profil/{id}", name="affiche_joueur", methods={"GET"})
     */
    public function afficheJoueur(Joueur $joueur): Response
    {
        return $this->render("joueur/affiche_profile.html.twig",[
            'joueur' => $joueur,
        ]);
    }

    /**
     * @Route("/modifier-profil/{id}", name="modifier_joueur", methods={"GET|POST"})
     */
    public function editerJoueur(Joueur $joueur,EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, Request $request,SluggerInterface $slugger): Response
    {
        $user = $this->getUser();

        if($user){
            $avatarOriginal = $joueur->getAvatar();
            $form = $this->createForm(JoueurType::class, $joueur,[
                'avatar' => $avatarOriginal
            ])->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()) {
    
                $file = $form->get('avatar')->getData();
    
                if($file){
                    $extension = ".".$file->guessExtension();
                    $safeFilename = $slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                    $newFilename = $safeFilename . '_' . uniqid() . $extension;
    
                    try {
    
                        $file->move($this->getParameter('avatars'), $newFilename);
                        $joueur->setAvatar($newFilename);
    
                    } catch (FileException $exception) {
                    }
    
                } else {
                    $joueur->setAvatar($avatarOriginal);
                }
    
                $entityManager->persist($joueur);
                $entityManager->flush();
    
                //$mdp aura comme valeur le nouveau mot de passe saisie au formulaire
                $mdp = $form->get('password')->getData();
            
                //Si les deux champs (nouveaux mot de passe et confirmer) sont saisies sans erreur
                //le mdp sera mis à jour. D'abord, on demande à Doctrine de sauvegarder le joueur (persist)
                //et ensuite exécuter la requête via flush
                if(!(empty($mdp))){
                    $joueur->setPassword($passwordHasher->hashPassword(
                        $joueur, $mdp
                        )
                    );
                    $entityManager->persist($joueur);
                    $entityManager->flush();
                }
    
                $this->addFlash('success',"Profil mis à jour !");
                return $this->render("joueur/affiche_profile.html.twig",[
                    'joueur' => $joueur,
                ]);
    
            }
    
            return $this->render('joueur/modifie_joueur.html.twig', [
                'form' => $form->createView(),
                'joueur' => $joueur
            ]);
        }
        else{
            return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/supprimer_joueur/{id}", name="supprimer_joueur", methods={"GET"})
     */
    public function supprimerJoueur(Joueur $joueur, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if($user){
            $this->entityManager->remove($joueur);
            $this->entityManager->flush();
    
            return $this->redirectToRoute('accueil');
        }
        else{
            return $this->redirectToRoute('accueil');
        }
    }


}
