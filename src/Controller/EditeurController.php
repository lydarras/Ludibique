<?php

namespace App\Controller;

use App\Entity\Editeur;
use App\Form\EditeurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/editeur")
 */
class EditeurController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="index_editeur", methods={"GET"})
     */
    public function indexEditeur(EntityManagerInterface $entityManager): Response
    {
        $editeurs = $entityManager->getRepository(Editeur::class)->findAll();
        return $this->render('admin/entite/editeur/index.html.twig', [
            'editeurs' => $editeurs
        ]);
    }

    /**
     * @Route("/nouveauEditeur", name="creer_editeur", methods={"GET", "POST"})
     */
    public function creerEditeur(Request $request, SluggerInterface $slugger): Response
    {
        $editeur = new Editeur();
        $form = $this->createForm(EditeurType::class, $editeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // On récupère le fichier du formulaire grâce à getData(). Cela nous retourne un objet de type UploadedFile.
            $file = $form->get('logo')->getData();

            // Condition qui vérifie si un fichier est présent dans le formulaire.
            if($file){

                // Générer une contrainte d'upload. On déclare un array avec deux valeurs de type string qui sont les
                // MimeType autorisés.
                // Vous retrouvez tous les MimeType existant sur internet (mozilla developper)
                // $allowedMimeType = ['image/jpeg', 'image/png'];

                // La fonction native in_array() permet de comparer deux valeurs (2 arguments attendus)
                // if(in_array($file->getMimeType(), $allowedMimeType)) {

                # Nous allons construire le nouveau nom du fichier :

                // On stocke dans une variable $originalFilename le nom du fichier.
                // On utilise encore une fonction native pathinfo()
                //  $originalFilename =  pathinfo( $file->getClientOriginalName(), PATHINFO_FILENAME);

                # Récupération de l'extension pour pouvoir reconstruire le nom quelques lignes après.
                // On utilise la concaténation pour ajouter un point '.'
                // déconstruit le nom du fichier et variabilise
                $extension = ".".$file->guessExtension();

                $originalFilename = pathinfo($file->getClientOriginalName());

                # Assainissement du nom grâce au slugger fourni par Symfony pour la construction du nouveau nom
                $safeFilename = $slugger->slug($editeur->getNom());
                //$safeFilename = $slugger->slug($originalFilename);

                # Construction du nouveau nom
                // uniqid() est une fonction native qui permet de générer un ID unique.
                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                // On utilise un try{} catch(){] lorsqu'on appelle une méthode qui lance une erreur.
                try {

                    /* On appelle la méthode move() de UploadedFile pour pouvoir déplacer le fichier
                        dans son dossier de destination.
                        Le dossier de destination a été paramétré dans services.yaml
                    /!\ ATTENTION :
                        La méthode move() lance une erreur de type FileException.
                        On attrape cette erreur dans le catch(FileException $exception)
                    */
                    $file->move($this->getParameter('uploads_dir'), $newFilename);

                    // On set la nouvelle valeur (nom du fichier) de la propiété picture de notre objet Article.
                    $editeur->setLogo($newFilename);

                } catch (FileException $exception) {
                // code à éxécuter si une erreur est attrapée.
                }

            }

            $this->entityManager->persist($editeur);
            $this->entityManager->flush();
            $this->addFlash('success',"L'éditeur a été crée");

            return $this->redirectToRoute('index_editeur');
        }

        return $this->render('admin/entite/editeur/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/afficher_editeur/{id}", name="afficher_editeur", methods={"GET"})
     */
    public function afficheEditeur(Editeur $editeur): Response
    {
        return $this->render('admin/entite/editeur/affiche.html.twig', [
            'editeur' => $editeur,
        ]);
    }

    /**
     * @Route("/modifier_editeur/{id}", name="modifier_editeur", methods={"GET", "POST"})
     */
    public function modifierEditeur(Request $request, Editeur $editeur, SluggerInterface $slugger): Response
    {

        $originalLogo= $editeur->getLogo() ?? '' ;

        $form = $this->createForm(EditeurType::class, $editeur,[
            'logo' => $originalLogo
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('logo')->getData();

            if($file){
                $extension = ".".$file->guessExtension();

                $originalFilename = pathinfo($file->getClientOriginalName());

                $safeFilename = $slugger->slug($editeur->getNom());

                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {

                    $file->move($this->getParameter('uploads_dir'), $newFilename);
                    $editeur->setLogo($newFilename);

                } catch (FileException $exception) {
                    
                }

            } else {
                $editeur->setLogo($originalLogo);
            }

            $this->entityManager->persist($editeur);
            $this->entityManager->flush();
            $this->addFlash('success',"L'éditeur a été édité");
            return $this->redirectToRoute('index_editeur', [], Response::HTTP_SEE_OTHER);

        }
            
        return $this->render('admin/entite/editeur/form.html.twig', [
            'form' => $form->createView(),
            'editeur' => $editeur
        ]);

    
    }

    /**
     * @Route("/supprimer_editeur/{id}", name="supprimer_editeur", methods={"GET"})
     */
    public function supprimerEditeur(Editeur $editeur): Response
    {
        $this->entityManager->remove($editeur);
        $this->entityManager->flush();

        return $this->redirectToRoute('index_editeur');
    }
}
