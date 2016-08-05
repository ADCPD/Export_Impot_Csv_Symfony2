<?php

namespace CMS\EquipeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class GalleryEquipesType
 * @package CMS\EquipeBundle\Form
 */
class GalleryEquipesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\EquipeBundle\Entity\GalleryEquipes'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'EquipeBundle_galleryequipes';
    }
}
