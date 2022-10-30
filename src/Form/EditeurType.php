<?php

namespace App\Form;

use App\Entity\Editeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'éditeur',
                //empty data permet de valider une saisie vide et affichera le message d'erreur de
                // l'utilisateur au lieu le message d'erreur de Symfony
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de remplir de champ"
                    ]),
                ],
            ])
            ->add('logo',FileType::class, [
                //paramètre le type de class à null (default : data_class = File)
                'data_class' => null,
                'label' => 'Logo de l\'éditeur',
                'attr' => [
                    'data-default-file' => $options['logo'],
                ],
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Les types de fichier autorisés sont : .jpeg / .png'
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Editeur::class,
            //autorise les upload de fichier dans le formulaire
            'allow_file_upload' => true,
            //récupère la photo existante durant l'update
            'logo' => null,
        ]);
    }
}
