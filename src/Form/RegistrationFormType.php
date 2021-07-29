<?php

namespace App\Form;

use DateTime;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champs ne peut pas être vide."
                    ]),
                    new Regex([
                        'pattern' => "/^[A-Za-zÀ-ÖØ-öø-ÿ]+$/i",
                        "message" => "Le nom renseigné est invalide."
                    ]),
                ]
            ])
            ->add('lastName', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champs ne peut pas être vide."
                    ]),
                    new Regex([
                        'pattern' => "/^[A-Za-zÀ-ÖØ-öø-ÿ]+$/i",
                        "message" => "Le nom renseigné est invalide."
                    ]),
                ]
            ])
            ->add('birthDate', null, [
                'years' => range(intval(date("Y", strtotime("-70 year"))), intval(date("Y", strtotime("-12 year")))),
                'constraints' => [
                    new LessThanOrEqual([
                        'value' => new DateTime('now -12 year'),
                        'message' => "Vous devez au moins avoir 12 ans pour faire une demande d'adhésion."
                    ])
                ]
            ])
            ->add('email', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champs ne peut pas être vide."
                    ]),
                    new Email([
                        'message' => "Adresse email invalide."
                    ])
                ]
            ])
            ->add('address', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champs ne peut pas être vide."
                    ])
                ]
            ])
            ->add('phoneNumber', TelType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champs ne peut pas être vide."
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Veuillez accepter les conditions d'utilisation.",
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => "Mot de passe",
                    'attr' => ['autocomplete' => 'new-password'],
                ],
                'second_options' => [
                    'label' => "Vérifiez votre mot de passe",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'invalid_message' => "Les mots de passe ne coincident pas."
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
