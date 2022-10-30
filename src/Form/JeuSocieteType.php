<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Editeur;
use App\Entity\JeuSociete;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class JeuSocieteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du jeu',
                //empty data permet de valider une saisie vide et affichera le message d'erreur de
                // l'utilisateur au lieu le message d'erreur de Symfony
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de remplir de champ"
                    ]),
                ],
            ])
            ->add('image',FileType::class, [
                //paramètre le type de class à null (default : data_class = File)
                'data_class' => null,
                'label' => 'L\'image du jeu',
                'attr' => [
                    'data-default-file' => $options['image'],
                ],
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Les types de fichier autorisés sont : .jpeg / .png'
                    ]),
                ],
            ])
            ->add('editeur', EntityType::class, [
                'class' => Editeur::class,
                'choice_label' => 'nom',
                'label' => 'Editeur de ce jeu'
            ])
            ->add('description',TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Description du jeu'
                ],
                //les contraintes de validation de content est dans l'Article (propriété $content)
            ])
            ->add('age_min', NumberType::class, [
                'label' => 'Age minimum',
                //empty data permet de valider une saisie vide et affichera le message d'erreur de
                // l'utilisateur au lieu le message d'erreur de Symfony
                'empty_data' => '',
                'invalid_message' => 'L\'âge minimum devrait obligatoirement être un chiffre',
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de remplir de champ"
                    ])
                ],
            ])
            ->add('nb_joueur_min', NumberType::class, [
                'label' => 'Nombre de joueur minimum',
                //empty data permet de valider une saisie vide et affichera le message d'erreur de
                // l'utilisateur au lieu le message d'erreur de Symfony
                'empty_data' => '',
                'invalid_message' => 'Le nombre de joueur minimum devrait obligatoirement être un chiffre',
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de remplir de champ"
                    ])
                ],
            ])
            ->add('nb_joueur_max', NumberType::class, [
                'label' => 'Nombre de joueur maximum',
                //empty data permet de valider une saisie vide et affichera le message d'erreur de
                // l'utilisateur au lieu le message d'erreur de Symfony
                'empty_data' => '',
                'invalid_message' => 'Le nombre de joueur max devrait obligatoirement être un chiffre',
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de remplir de champ"
                    ])
                ],
            ])
            ->add('duree_estimee', NumberType::class, [
                'label' => 'Durée estimée (en minutes)',
                //empty data permet de valider une saisie vide et affichera le message d'erreur de
                // l'utilisateur au lieu le message d'erreur de Symfony
                'empty_data' => '',
                'invalid_message' => 'La durée estimée devrait obligatoirement être un chiffre',
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de remplir de champ"
                    ]),
                ],
            ])
            ->add('auteurJeu', EntityType::class, array(
                'class'     => Auteur::class,
                'expanded'  => true,
                'mapped' => true,
                'multiple'  => true,
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JeuSociete::class,
            //autorise les upload de fichier dans le formulaire
            'allow_file_upload' => true,
            //récupère la photo existante durant l'update
            'image' => null,
        ]);
    }
}
