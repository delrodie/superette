<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InventorierType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->inventaire =$options['inventaire'];
        $inventaire = $this->inventaire;
        $builder
            ->add('quantite', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Quantite de produit", 'autocomplete'=>"off"]])
            ->add('montant', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Montant du produit", 'autocomplete'=>"off"]])
            //->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('inventaire', EntityType::class,[
                'attr'=>['class'=>'form-control'],
                'class'=> 'AppBundle:Inventaire',
                'query_builder'=> function(EntityRepository $er) use($inventaire){
                    return $er->findInventaire($inventaire);
                },
                'choice_label' =>'reference'
            ])
            ->add('produit', null,[
                'attr'=>['class'=>'form-control js-select2'],
                'class'=> 'AppBundle:Produit',
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
            'data_class' => 'AppBundle\Entity\Inventorier',
            'inventaire' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_inventorier';
    }


}
