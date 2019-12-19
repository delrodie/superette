<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('reference')
            ->add('codebarre', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>"off", 'placeholder'=>"Code barre"], 'required'=>false])
            ->add('libelle', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>"off", 'placeholder'=>"Nom"]])
            ->add('prixAchat', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>"off", 'placeholder'=>"Prix d'achat"], 'required'=>false])
            ->add('prixVente', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>"off", 'placeholder'=>"Prix de vente"], 'required'=>false])
            ->add('stock', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>"off", 'placeholder'=>"Stock"], 'required'=>false])
            ->add('seuil', TextType::class,['attr'=>['class'=>'form-control', 'autocomplete'=>"off", 'placeholder'=>"Seuil d'approvisionnement"], 'required'=>false])
            //->add('slug')->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('categorie', EntityType::class,[
                'attr'=>['class'=>'form-control js-select2'],
                'class'=> 'AppBundle:Categorie',
                'query_builder'=> function(EntityRepository $er){
                    return $er->liste();
                },
                'choice_label' =>'libelle'
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_produit';
    }


}
