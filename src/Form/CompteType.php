<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Compte;
use App\Entity\TypeCompte;
use App\Repository\CompteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompteType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
        
        $builder
            ->add('type_Compte',EntityType::class,array('class'=>TypeCompte::class,'label'=>'Type Compte', 'attr'=>array('class'=>'form-control form-group')))
            //->add('numeroCompte',TextType::class,array('label'=>'Numero du compte','required'=>False, 'attr'=>array('class'=>'form-control form-group')))
            //->add('dateCreation',DateType::class,array('label'=>"Date de creation", 'attr'=>array('class'=>'form-control form-group')))
            //->add('solde',TextType::class,array('label'=>'Solde','required'=>False, 'attr'=>array('class'=>'form-control form-group')))
            //->add('etatCompte',TextType::class,array('label'=>'etat compte','required'=>False, 'attr'=>array('value'=>'actif','class'=>'form-control form-group')))
            ->add('client',ClientType::class,array('label'=>'client','required'=>False))
            
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
