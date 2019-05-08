<?php

namespace App\MarvelPassion\UserBundle\Form;

use App\MarvelPassion\UserBundle\Entity\Role;
use App\MarvelPassion\UserBundle\Entity\Users;
use App\MarvelPassion\UserBundle\Repository\RoleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role_id', ChoiceType::class, [
                    'choices' => $options['data'],
                    'mapped' =>false,
                    'label' => 'Rôle'
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
