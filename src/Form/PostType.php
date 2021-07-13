<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Écrivez le titre de votre publication ici'
                ]
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'placeholder' => 'Redigez votre article ici'
                ],
                // 'help' => 'somethin',
                // 'help_attr' => [
                //     'class' => 'text-muted'
                // ]
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie de votre publication',
                'choices' => [
                    'Educatif' => 0,
                    'Cuisine' => 1,
                    'Cat2' => 2,
                    'Cat3' => 3,
                    'Cat4' => 4,
                ],
                // 'help' => 'Si vous êtes enseignant et que votre publication concerne une activité, sélectionnez l'option "Educatif"' 
            ])
            ->add('keywords', TextType::class, [
                'property_path' => 'keywords[0]',
                'label' => 'Mots clés (séparés par virgule ",")',
                'attr' => [
                    'placeholder' => 'Exemple : Chocolat, Sucré, Vanille',
                ]
                // 'constraints' => [
                //     new NotBlank([
                //         'message' => 'Il faut au moins un mot clé'
                //     ])
                // ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
