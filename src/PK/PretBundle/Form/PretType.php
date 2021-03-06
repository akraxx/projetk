<?php

namespace PK\PretBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PretType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('objet')
            ->add('emprunteur')
            ->add('typeEmprunteur')
            ->add('emailEmprunteur')
            ->add('userEmprunteur')
            ->add('pretParent')
            ->add('date','date',array('required' => false,
                                        'widget' =>'single_text',
                                        'format' =>'dd-MM-yyyy'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PK\PretBundle\Entity\Pret'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pk_pretbundle_pret';
    }
}
