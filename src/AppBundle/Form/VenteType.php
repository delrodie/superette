<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VenteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->facture =$options['facture'];
        $facture = $this->facture;
        $builder
            ->add('quantite', IntegerType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Qte", 'autocomplete'=>'off'],'required'=>false])
            //->add('montant', TextType::class,['attr'=>['class'=>'form-control']])
            //->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('facture', EntityType::class,[
                'attr'=>['class'=>'form-control'],
                'class'=> 'AppBundle:Facture',
                'query_builder'=> function(EntityRepository $er) use($facture){
                    return $er->findFacture($facture);
                },
                'choice_label' =>'reference'
            ])
            //->add('produit')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Vente',
            'facture' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_vente';
    }


}
