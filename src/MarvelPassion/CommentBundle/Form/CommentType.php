<?php

namespace App\MarvelPassion\CommentBundle\Form;

use App\MarvelPassion\CommentBundle\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder
                ->add('content', null, array(
                    'label'       =>  'Laisser un commentaire :',
                    'label_attr' => array('class' => 'label-comment'),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
