<?php

namespace App\Form\Account;

use App\Dto\Account\TransferData;
use App\Entity\Account\Account;
use App\Repository\Account\AccountRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransferType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
            ])
            ->add('total', MoneyType::class, [
                'label' => 'Amount',
                'required' => false
            ])
            ->add('time', DateTimeType::class, [
                'label' => 'Date',
                'widget' => 'single_text'
            ])
            ->add('source', EntityType::class, [
                'label' => 'Source',
                'class' => Account::class,
                'query_builder' => static function (AccountRepository $accountRepository) {
                    return $accountRepository->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('target', EntityType::class, [
                'label' => 'Target',
                'class' => Account::class,
                'query_builder' => static function (AccountRepository $accountRepository) {
                    return $accountRepository->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => TransferData::class,
        ]);
    }
}
