<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FournisseurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'attr'=>['class'=>'form-control form-control-lg', 'placeholder'=>"Le nom du fournisseur", 'autocomplete'=>'off']
            ])
            ->add('contact', TextType::class,[
                'attr'=>['class'=>'form-control form-control-lg', 'placeholder'=>"Le contact du fournisseur", 'autocomplete'=>'off'], 'required'=>false
            ])
            ->add('email', EmailType::class,[
                'attr'=>['class'=>'form-control form-control-lg', 'placeholder'=>"L'email du fournisseur", 'autocomplete'=>'off'], 'required'=>false
            ])
            ->add('adresse', TextType::class,[
                'attr'=>['class'=>'form-control form-control-lg', 'placeholder'=>"L'adresse du fournisseur", 'autocomplete'=>'off'], 'required'=>false
            ])
            ->add('statut', CheckboxType::class,[
                'attr'=>['class'=>'custom-control-input'], 'required'=>false
            ])
            //->add('slug')->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Fournisseur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_fournisseur';
    }


}
