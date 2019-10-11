<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InventaireType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', TextType::class,['attr'=>['class'=>'js-datepicker form-control', 'autocomplete'=>'off', 'placeholder'=>"La date de facture"]])
            ->add('montant', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Le montant total de l'achat", 'autocomplete'=>"off"]])
            ->add('refFournisseur', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Le numero facture du fournisseur", 'autocomplete'=>"off"]])
            //->add('deduction')->add('nombreProduit')->add('flag')
            //->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('fournisseur', EntityType::class,[
                'attr'=>['class'=>'form-control js-select2'],
                'class'=> 'AppBundle:Fournisseur',
                'query_builder'=> function(EntityRepository $er){
                    return $er->liste();
                },
                'choice_label' =>'nom'
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Inventaire'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_inventaire';
    }


}
