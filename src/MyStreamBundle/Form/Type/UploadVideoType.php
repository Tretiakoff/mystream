<?php

namespace MyStreamBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UploadVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('label' => false, 'attr' => array('placeholder' => 'Titre')))
            ->add('description', TextType::class, array('label' => false, 'attr' => array('placeholder' => 'Description')))
            ->add('video', 'vich_file', array(
                'required' => true,
                'allow_delete' => true, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
                'label' => 'Video'
            ))
            ->add('image', 'vich_image', array(
                'required' => true,
                'allow_delete' => true, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
                'label' => 'Vignette'
            ))
            ->add('save', SubmitType::class, array('label' => 'Upload'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EntityBundle\Entity\Video',
        ));
    }
}
