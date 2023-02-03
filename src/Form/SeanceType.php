<?php

namespace App\Form;

use App\Entity\JeuSociete;
use App\Entity\Seance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la séance',
                //empty data permet de valider une saisie vide et affichera le message d'erreur de
                // l'utilisateur au lieu le message d'erreur de Symfony
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de remplir de champ"
                    ]),
                ],
            ])
            ->add('jeu', EntityType::class, [
                'class' => JeuSociete::class,
                'choice_label' => 'nom',
                'label' => 'Choix de jeu'
            ])
            ->add('description',TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Description du jeu'
                ],
                //les contraintes de validation de content est dans l'Article (propriété $content)
            ])
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'format' => 'dd-MM-yyyy'
            ])
            ->add('heure', TimeType::class, [
                'widget' => 'choice',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
