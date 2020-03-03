<?php

namespace App\Form\Transaction;

use App\Dto\Transaction\IndebtednessData;
use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Entity\Tag\Tag;
use App\Entity\TaxPayer\TaxPayer;
use App\Repository\Account\AccountRepository;
use App\Repository\Category\CategoryRepository;
use App\Repository\Tag\TagRepository;
use App\Repository\TaxPayer\TaxPayerRepository;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;

class IndebtednessType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) :void{
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => false
            ])
            ->add('total', MoneyType::class, [
                'label' => 'Total',
                'help' => 'Use a positive value if you will receive money. Use a negative number if you will spend money.'
            ])
            ->add('time', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'help' => 'When do you estimate this indebtedness will become a transaction. Ie. when do you think the payment will be made. Used to create a remainder',
                'constraints' => [
                    new GreaterThan(['value' => new DateTime(), 'message' => 'The date should be in the future.'])
                ]
            ])
            ->add('account', EntityType::class, [
                'label' => 'Account',
                'class' => Account::class,
                'query_builder' => static function (AccountRepository $accountRepository) {
                    return $accountRepository->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('taxPayer', EntityType::class, [
                'label' => 'Tax Payer',
                'class' => TaxPayer::class,
                'query_builder' => static function (TaxPayerRepository $taxPayerRepository) {
                    return $taxPayerRepository->createQueryBuilder('tp')
                        ->where('tp.enabled = true')
                        ->orderBy('tp.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('category', EntityType::class, [
                'label' => 'Category',
                'class' => Category::class,
                'query_builder' => static function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Tags',
                'class' => Tag::class,
                'query_builder' => static function (TagRepository $tagRepository) {
                    return $tagRepository->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) :void {
        $resolver->setDefaults([
            'data_class' => IndebtednessData::class,
        ]);
    }
}
