<?php

namespace App\MarvelPassion\UserBundle\Form;

use App\MarvelPassion\UserBundle\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array('label' => 'Prénom'))
            ->add('lastName', TextType::class, array('label' => 'Nom'))
            ->add('pseudo', TextType::class, array('label' => 'Pseudo'))
            ->add('email', EmailType::class, array('label' => 'E-mail'))
            ->add('avatar', FileType::class, array('label' => 'Image de profil', 'required' => false, 'data_class' => null, 'mapped'=>false))
            ->add('description', TextareaType::class, array('label' => 'Description', 'required' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
