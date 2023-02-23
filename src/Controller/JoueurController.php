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
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
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



            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

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

            $mdp = $form->get('plainPassword')->getData();

            if($mdp){
                $joueur->setPassword($passwordHasher->hashPassword(
                    $joueur, $form->get('plainPassword')->getData()
                    )
                );
            }

            $entityManager->persist($joueur);
            $entityManager->flush();

            return $this->redirectToRoute('affiche_joueur', array(
                'id' => $joueur->getId()
            ));
        }

        return $this->render('joueur/modifie_joueur.html.twig', [
            'form' => $form->createView(),
            'joueur' => $joueur
        ]);
    }


}
