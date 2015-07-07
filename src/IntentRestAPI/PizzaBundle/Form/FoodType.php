<?php
/**
 * Created by PhpStorm.
 * User: laurentbrau
 * Date: 17/05/15
 * Time: 13:14
 */
namespace IntentRestAPI\PizzaBundle\Form;
use Symfony\Component\Form\FormBuilderInterface;


class FoodType extends \IntentRestAPI\MainBundle\Form\ProduitType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('type')
            ->add('taille')
        ;
    }
}