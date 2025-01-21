<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,[
                'label' => 'Saisir votre Prénom :'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Saisir votre Nom :'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Saisir votre Email :'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Saisir votre mot de passe', 'hash_property_path' => 'password'],
                'second_options' => ['label' => 'Confirmer votre mot de passe'],
                'mapped' => false,
                'invalid_message' => 'Les mots de passe ne correspondent pas'
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['value'=> 'Ajouter']
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