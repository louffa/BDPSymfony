<?php

namespace App\Form;

use App\Entity\Compte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheCompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroCompte',TextType::class,array('label'=>'Numero du compte','attr'=>array('class'=>'form-control form-group')))
            ->add('Valider', SubmitType::class, array('attr'=>array('class'=>'btn btn-success form-group')))
            ->add('Annuler', ResetType::class, array('attr'=>array('class'=>'btn btn-danger form-group')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }
}
