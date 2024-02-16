<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['placeholder' => 'Nom']
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['placeholder' => 'Prenom']
            ])
            ->add('email', TextType::class, [
                'attr' => ['placeholder' => 'Email']
            ])
            ->add('password', PasswordType::class, ['attr' => ['placeholder' => 'Mot de passe']])
            ->add('Enregistrer', SubmitType::class,['attr' => [ 'class' => 'btn bright-btn create-user-btn']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
