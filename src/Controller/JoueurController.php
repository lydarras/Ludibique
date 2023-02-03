<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
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
            // encode the plain password
            //$user->setRoles(["ROLE_USER"]);
            //dd($user);
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
     * @Route("/mon-profil", name="affiche_joueur", methods={"GET"})
     */
    public function afficheJoueur(EntityManagerInterface $entityManager): Response
    {

        return $this->render("joueur/affiche_profile.html.twig");
    }

    /**
     * @Route("/modifier-profil", name="modifier_joueur", methods={"GET|POST"})
     */
    public function editerJoueur(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, Request $request): Response
    {
        $form = $this->createForm(JoueurType::class)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $joueur = $entityManager->getRepository(Joueur::class)->findOneBy(['id' => $this->getUser()]);

            $joueur->setPassword($passwordHasher->hashPassword(
                $joueur, $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($joueur);
            $entityManager->flush();

            $this->addFlash('success', "Votre mot de passe a bien été changé");
            return $this->redirectToRoute('affiche_joueur');
        }

        return $this->render('joueur/modifie_joueur.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
