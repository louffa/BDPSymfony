<?php

namespace App\Form;

use App\Entity\Client;
use phpDocumentor\Reflection\Types\False_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType2 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cni',TextType::class,array('label'=>'CNI du client', 'attr'=>array('class'=>'form-control form-group')))
            ->add('nom',TextType::class,array('label'=>'Nom du client', 'attr'=>array('require'=>'require','class'=>'form-control form-group')))
            ->add('prenom',TextType::class,array('label'=>'Prenom du client', 'attr'=>array('require'=>'require','class'=>'form-control form-group')))
            ->add('email',TextType::class,array('label'=>'Email du client', 'attr'=>array('require'=>'require','class'=>'form-control form-group')))
            ->add('telephone',TextType::class,array('label'=>'Telephone du client', 'attr'=>array('require'=>'require','class'=>'form-control form-group')))
            ->add('salaire',TextType::class,array('label'=>'Salaire du client','required'=>False, 'attr'=>array('PlaceHolder'=>'--Optionnel--','class'=>'form-control form-group')))
            ->add('profession',TextType::class,array('label'=>'Profession du client','required'=>False, 'attr'=>array('PlaceHolder'=>'--Optionnel--','class'=>'form-control form-group')))
            ->add('employeur',EmployeurType::class,array('label'=>'Employeur','required'=>False))
            ->add('Valider', SubmitType::class, array('attr'=>array('class'=>'btn btn-success form-group')))
            ->add('Annuler', ResetType::class, array('attr'=>array('class'=>'btn btn-danger form-group')))
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
