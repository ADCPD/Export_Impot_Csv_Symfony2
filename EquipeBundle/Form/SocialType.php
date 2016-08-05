<?php

namespace CMS\EquipeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class SocialType
 * @package CMS\EquipeBundle\Form
 */
class SocialType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array(
                    'label' => 'Joindre un reseau social',
                    'choices' => array(
                        'LinkedIn' => "LinkedIn",
                        'GooglePlus' => 'Google+',
                        'Facebook' => 'Facebook'
                    )

                )
            )
            ->add('url', "url", array(
                'label' => ' Lien',
                'attr' => array(
                    'class' => 'control-group'
                )));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\EquipeBundle\Entity\Social'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'EquipeBundle_social';
    }
}
