<?php

namespace App\Form;

use App\Entity\Movies;
use App\Entity\Series;
use App\Entity\Shootings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ShootingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Titre'))
            ->add('description', null, array('label' => 'Description', 'required' => false))
            ->add('image', FileType::class, array('label' => 'Image', 'required' => false, 'data_class' => null, 'mapped'=> false))
            ->add('lat',  null, array('label' => 'Latitude', 'invalid_message' => 'Vous ne pouvez pas entrer des lettres, seulement les chiffres sont acceptés.'))
            ->add('lng', null, array('label' => 'Longitude', 'invalid_message' => 'Vous ne pouvez pas entrer des lettres, seulement les chiffres sont acceptés.'))
            ->add('address', null, array('label' => 'Adresse'))
            
            ->add('movie', EntityType::class, array(
                'class'        => Movies::class,
                'multiple'     => false,
                'label' => 'Relation avec un film :',
                'choice_label' => 'title',
                'placeholder' => 'Aucun film séléctionné',
                'required' => false
              ))

             ->add('serie', EntityType::class, array(
                'class'        => Series::class,
                'multiple'     => false,
                'label' => 'Relation avec une série :',
                'choice_label' => 'title',
                'placeholder' => 'Aucune série séléctionnée',
                'required' => false
              ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Shootings::class,
        ]);
    }
}
