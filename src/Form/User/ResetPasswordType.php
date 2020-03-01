<?php

namespace App\Form\User;

use App\Dto\User\ResetPasswordData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The field "New password" and "Repeat new password" should be equal.',
                'first_options' => ['label' => false, 'attr' => ['placeholder' => 'Nova password'],],
                'second_options' => ['label' => false, 'attr' => ['placeholder' => 'Repetir nova password'],],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) :void {
        $resolver->setDefaults([
            'data_class' => ResetPasswordData::class,
        ]);
    }
}
