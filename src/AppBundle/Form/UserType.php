<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
              'attr'  => array(
                  'class' => 'form-control form-control-lg',
                  'autocomplete'  => 'off',
                  'placeholder' => 'Nom utilisateur'
              )
        ))
            //->add('usernameCanonical')
            ->add('email', EmailType::class, array(
              'attr'  => array(
                  'class' => 'form-control form-control-lg',
                  'autocomplete'  => 'off',
                  'placeholder' => "Adresse email"
              )
        ))
            //->add('emailCanonical')
            ->add('enabled', CheckboxType::class,[
                'attr'=>['class'=>'custom-control-input'], 'required'=>false
            ])
            ->add('password', PasswordType::class,[
                'attr'=>['class'=>'form-control form-control-lg', 'placeholder'=>"Mot de passe"], 'required'=>false
            ])
            /*->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                'required' => $options['passwordRequired'],
                'first_options'  => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Répétez le mot de passe'),
            ))*/
            //->add('lastLogin')->add('confirmationToken')->add('passwordRequestedAt')
            ->add('roles', ChoiceType::class, array(
              'choices' => array(
                'UTILISATEUR '  => 'ROLE_USER',
                'CAISSE '  => 'ROLE_CAISSE',
                'RAYON '  => 'ROLE_RAYON',
                'ADMINISTRATEUR '  => 'ROLE_ADMIN',
              ),
              'attr'  => array(
                  'autocomplete'  => 'off'
              ),
              'multiple'  => true,
              'expanded'  => true
        ))
            //->add('loginCount')->add('firstLogin')
            ;
            if ($options['lockedRequired']) {
                $builder->add('locked', null, array('required' => false,
                    'label' => 'Vérouiller le compte'));
            }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'passwordRequired' => true,
            'lockedRequired' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
