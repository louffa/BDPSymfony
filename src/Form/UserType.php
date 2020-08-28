<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\Roles;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',TextType::class,array('label'=>'Email', 'attr'=>array('class'=>'form-control form-group')))
            ->add('password', PasswordType::class,array('label'=>'Mot de Passe', 'attr'=>array('class'=>'form-control form-group')))
            ->add('nom',TextType::class,array('label'=>'nom', 'attr'=>array('class'=>'form-control form-group')))
            ->add('prenom',TextType::class,array('label'=>'prenom', 'attr'=>array('class'=>'form-control form-group')))
            //->add('roles', EntityType::class,array('class'=>Roles::class,'label'=>'Type Operation', 'attr'=>array('class'=>'form-control form-group')))
            ->add('agence',EntityType::class,array('class'=>Agence::class,'label'=>'Type Operation', 'attr'=>array('class'=>'form-control form-group')))
            ->add('Valider', SubmitType::class, array('attr'=>array('class'=>'btn btn-success form-group')))
            ->add('Annuler', ResetType::class, array('attr'=>array('class'=>'btn btn-danger form-group')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
