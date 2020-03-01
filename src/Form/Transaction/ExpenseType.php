<?php

namespace App\Form\Transaction;

use App\Dto\Transaction\ExpenseData;
use App\Entity\Account\Account;
use App\Entity\TaxPayer\TaxPayer;
use App\Repository\Account\AccountRepository;
use App\Repository\TaxPayer\TaxPayerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpenseType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options):void {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => false
            ])
            ->add('total', MoneyType::class, [
                'label' => 'Total'
            ])
            ->add('time', DateTimeType::class, [
                'label' => 'Date',
                'widget' => 'single_text'
            ])
            ->add('account', EntityType::class, [
                'label' => 'Source',
                'class' => Account::class,
                'query_builder' => static function (AccountRepository $accountRepository) {
                    return $accountRepository->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('taxPayer', EntityType::class, [
                'label' => 'Target',
                'class' => TaxPayer::class,
                'query_builder' => static function (TaxPayerRepository $taxPayerRepository) {
                    return $taxPayerRepository->createQueryBuilder('tp')
                        ->where('tp.enabled = true')
                        ->orderBy('tp.name', 'ASC');
                },
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => ExpenseData::class,
        ]);
    }
}
