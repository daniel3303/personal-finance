<?php

namespace App\Form\User;

use App\Entity\User;
use App\Form\Model\ChangePassword;
use App\Form\Model\ResetPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'O campo "Nova password" e "Repetir nova password" devem iguais.',
                'first_options' => ['label' => false, 'attr' => ['placeholder' => 'Nova password'],],
                'second_options' => ['label' => false, 'attr' => ['placeholder' => 'Repetir nova password'],],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ResetPassword::class,
        ]);
    }
}
