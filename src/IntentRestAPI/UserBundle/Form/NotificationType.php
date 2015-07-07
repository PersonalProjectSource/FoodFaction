<?php

namespace IntentRestAPI\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NotificationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture')
            ->add('contenu')
            ->add('date')
            ->add('typeNotif')
            ->add('user')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IntentRestAPI\UserBundle\Entity\Notification'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'intentrestapi_userbundle_notification';
    }
}
