<?php

namespace App\Form\User;

use App\Dto\User\UserData;
use App\Form\Type\GenderType;
use App\Form\Type\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        /** @var UserData|null $user */
        $user = $options['data'] ?? null;
        $isEdit = $user->getEntity() && $user->getEntity()->getId();

        $builder->add('enabled', CheckboxType::class, [
            'label' => 'Enabled',
            'required' => false,
        ]);

        $builder->add('photo', ImageType::class, [
            'context' => 'profile_photo',
            'label' => false,
            'required' => false,
            'file_options' => [
                'label' => false,
            ],
            'checkbox_options' => [
                'label' => 'Remover',
            ],
        ]);

        $builder->add('name', TextType::class, [
            'label' => 'Name',
            'help' => 'User\'s full name.',
        ]);

        if ($isEdit === false) {
            $builder->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The field "Password" and "Repeat password" must be equal.',
                'first_options' => ['label' => 'Password', 'help' => 'User\'s password.'],
                'second_options' => ['label' => 'Repeat password', 'help' => 'Repeat the user\'s password.'],
            ]);
        }

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'help' => 'User\'s email, used for authentication.'
            ]);

        $builder->add('gender', GenderType::class, [
            'label' => 'Gender',
        ]);
        $builder->add('birthday', DateType::class, [
            'label' => 'Birthday',
            'widget' => 'single_text',
        ]);

        $builder
            ->add('language', LanguageType::class, [
                'label' => 'Language',
            ])
            ->add('currencyCode', CurrencyType::class, [
                'label' => 'Currency',
            ])
            ->add('timezone', TimezoneType::class, [
                'label' => 'timezone',
            ]);

        if ($options['allow_change_roles']) {
            $builder->add('roles', ChoiceType::class, [
                'label' => 'Permissions',
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Administrator' => 'ROLE_ADMIN',
                ],
                'help' => 'Permissions for this user. Administrator provides access to this panel.',
                'multiple' => true,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => UserData::class,
            'allow_change_roles' => false,
        ]);
    }
}
