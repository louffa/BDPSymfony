<?php

namespace App\Form;

use App\Entity\Compte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheVirementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroCompteDebit',TextType::class,array('label'=>'Numero du compte débiteur','attr'=>array('class'=>'form-control form-group')))
            ->add('numeroCompteCredit',TextType::class,array('label'=>'Numero du compte créditeur','attr'=>array('class'=>'form-control form-group')))
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }
}
