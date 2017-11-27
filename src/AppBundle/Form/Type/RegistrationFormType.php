<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RegistrationFormType extends AbstractType
{
    /**
     * @var string
     */
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',  EmailType::class, [
                'label' => 'Ваш E-mail',
                'attr' => [
                    'data-form-email' => '',
                    'class' => 'form-control',
                    'placeholder' => 'E-mail'
                ]
            ])
            ->add('username',  HiddenType::class, [
                'attr' => [
                    'data-form-username' => '',
                ]
            ])
            ->add('plainPassword',  PasswordType::class, [
                'label' => 'Пароль',
                'attr' => [
                    'data-form-password' => '',
                    'class' => 'form-control',
                    'placeholder' => 'Пароль'
                ]
            ])
            ->add('type',  ChoiceType::class, [
                'label' => 'Кто вы?',
                'choices' => [
                    'Пожертвователь' => 'contributor',
                    'Детский дом' => 'ward',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'cursor: pointer;'
                ]
            ])
           ;
    }

    public function getBlockPrefix()
    {
        return 'app_registration';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'csrf_token_id' => 'registration',
            // BC for SF < 2.8
            'intention' => 'registration',
        ));
    }

    // BC for SF < 3.0
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
