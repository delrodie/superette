<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>'Nom de la categorie', 'autocomplete'=>"off"]])
            //->add('nombreProduit')
            ->add('domaine', EntityType::class,[
                'attr'=>['class'=>'form-control js-select2'],
                'class'=> 'AppBundle:Domaine',
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
            'data_class' => 'AppBundle\Entity\Categorie'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_categorie';
    }


}
