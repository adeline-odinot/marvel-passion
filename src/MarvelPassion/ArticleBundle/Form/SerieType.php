<?php

namespace App\MarvelPassion\ArticleBundle\Form;

use App\MarvelPassion\ArticleBundle\Entity\Series;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Titre'))
            ->add('introduction', null, array('label' => 'Introduction', 'required' => false))
            ->add('content', null, array('label' => 'Contenu', 'required' => false))
            ->add('image', FileType::class, array('label' => 'Image', 'required' => false, 'data_class' => null, 'mapped'=> false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Series::class,
        ]);
    }
}
