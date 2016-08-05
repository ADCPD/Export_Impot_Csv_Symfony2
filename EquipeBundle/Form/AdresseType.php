<?php

namespace CMS\EquipeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdresseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresse', 'text', array(
                'label' => 'Adresse postale',
                'attr' => array(
                    'class' => 'control-group searchTextField',
                    'placeholder' => 'Entrez votre adresse'
                )
            ))
            ->add('mail', 'email', array(
                'label' => 'Adresse E-mail',
                'attr' => array(
                    'class' => 'control-group',
                    'placeholder' => 'Entrez votre adresse mail'

                )
            ))
            ->add('tel', 'text', array(
                'label' => 'Numéro de portable',
                'attr' => array(
                    'class' => 'control-group',
                    'placeholder' => 'Entrez votre numéro de telephone '

                )
            ))
            ->add('fix', 'text', array(
                'label' => 'Numéro de fix',
                'attr' => array(
                    'class' => 'control-group',
                    'placeholder' => 'Entrez votre numéro de telephone '

                )
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\EquipeBundle\Entity\Adresse'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'EquipeBundle_adresse';
    }
}
