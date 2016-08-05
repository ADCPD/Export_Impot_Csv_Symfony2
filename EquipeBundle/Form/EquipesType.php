<?php

namespace CMS\EquipeBundle\Form;

use ProjetCollectionBundle\Form\GalleryType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class EquipesType
 * @package CMS\EquipeBundle\Form
 */
class EquipesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text', array(
                'label' => 'Non du collaborateur',
                'label_attr' => array(
                    'class' => 'control-label'
                ),
                'attr' => array(
                    'class' => 'control-group'
                )
            ))
            ->add('prenom', 'text', array(
                'label' => 'Prénom du collaborateur',
                'label_attr' => array(
                    'class' => 'control-label'
                ),
                'attr' => array(
                    'class' => 'control-group'
                )
            ))
            ->add('poste', 'text', array(
                'label' => 'Poste occupé',
                'label_attr' => array(
                    'class' => 'control-label'
                ),
                'attr' => array(
                    'class' => 'control-group'
                )
            ))
            ->add('apropos', 'textarea', array(
                'attr' => array(
                    'rows' => 10,
                    'cols' => 16
                )
            ))
            ->add('galleries', new GalleryEquipesType(), array(
                'label' => 'Photo de prefil',
                'required' => true,
            ))
            ->add('adresses', new AdresseType())
            ->add('socials', 'collection', array(
                'type' => new SocialType(),
                'allow_add' => true,
                'allow_delete' => true
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\EquipeBundle\Entity\Equipes'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'EquipeBundle_equipes';
    }
}
