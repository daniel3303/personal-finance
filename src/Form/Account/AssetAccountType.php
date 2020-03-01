<?php

namespace App\Form\Account;

use App\Dto\Account\AssetAccountData;
use App\Entity\Account\AssetAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssetAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
            ])
            ->add('total', MoneyType::class, [
                'label' => 'Initial money'
            ])
            ->add('initialAmountTime', DateTimeType::class, [
                'label' => 'Date of initial money',
                'widget' => 'single_text',
            ])
            ->add('iban', TextType::class, [
                'label' => 'Iban',
                'required' => false,
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Notes',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AssetAccountData::class,
        ]);
    }
}
