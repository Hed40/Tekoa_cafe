<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => 'true',
                'label' => 'Adresse E-mail'
            ])
            ->add('firstname', TextType::class, [
                'disabled' => 'true',
                'label' => 'Prénom'
            ])
            ->add('lastname', TextType::class, [
                'disabled' => 'true',
                'label' => 'Nom'
            ])
            ->add('old_password', PasswordType::class, [
                'mapped'=> false,
                'label' => 'Mon mot de passe actuel',
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' =>false, //Ne va pas chercher dans App/Entity/user
                'invalid_message' => 'Les mots de passes ne sont pas identique.',
                'label' => 'Votre nouveau mot de passe',
                'required' => true,
                'constraints'=> new Length([
                    'min'=> 2,
                    'max'=> 30
            ]), //Longueur de 2 min à 30 caractères Max
                'first_options'=> [
                    'label' => 'Nouveau Mot de passe',
                    'attr'=> [
                        'placeholder' => 'Veuillez de saisir votre nouveau mot de passe'
                    ]
                ],
                'second_options'=>[
                    'label'=> 'Confirmer votre nouveau mot de passe',
                    'attr'=> [
                        'placeholder' => 'Veuillez de confirmer votre nouveau mot de passe'
                    ]
                    ]
            ])
            ->add('submit',SubmitType::class, [
                'label' =>"Mettre à jour"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
