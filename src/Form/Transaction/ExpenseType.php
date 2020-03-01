<?php

namespace App\Form\Transaction;

use App\Entity\TaxPayer\TaxPayer;
use App\Entity\Transaction\Expense;
use App\Entity\Transaction\Revenue;
use App\Repository\TaxPayer\TaxPayerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpenseType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
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
            ->add('taxPayer', EntityType::class, [
                'label' => 'Source',
                'class' => TaxPayer::class,
                'query_builder' => static function (TaxPayerRepository $taxPayerRepository) {
                    return $taxPayerRepository->createQueryBuilder('tp')
                        ->where('tp.enabled = true')
                        ->orderBy('tp.name', 'ASC');
                },
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Expense::class,
        ]);
    }
}
